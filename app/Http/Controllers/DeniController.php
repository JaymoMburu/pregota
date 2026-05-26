<?php

namespace App\Http\Controllers;

use App\Models\Deni;
use App\Models\DeniPayment;
use App\Models\PayLink;
use App\Services\DarajaService;
use App\Services\SellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class DeniController extends Controller
{
    public function __construct(
        private DarajaService $daraja,
        private SellerService $seller,
    ) {}

    // Public creation page (no auth needed)
    public function create()
    {
        return view('deni.create');
    }

    // Anyone creates a tab — seller or vibanda owner
    public function store(Request $request)
    {
        $isSeller = session()->has('seller_id');
        $payLink  = $isSeller ? PayLink::findOrFail(session('seller_id')) : null;

        $rules = [
            'description'     => ['required', 'string', 'max:300'],
            'original_amount' => ['required', 'integer', 'min:1', 'max:500000'],
            'debtor_phone'    => ['nullable', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'due_date'        => ['nullable', 'date', 'after:today'],
        ];

        if (! $isSeller) {
            $rules['creditor_name']  = ['required', 'string', 'max:100'];
            $rules['lender_phone']   = ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'];
        }

        $data = $request->validate($rules);

        $debtorHash    = isset($data['debtor_phone']) ? $this->seller->hashPhone($data['debtor_phone']) : null;
        $creditorLabel = $payLink?->business_name ?? $data['creditor_name'];

        // Lender's M-Pesa: seller uses their registered phone, personal uses the provided one
        $lenderPhone = $isSeller
            ? Crypt::decryptString($payLink->phone_encrypted)
            : $data['lender_phone'];

        $deni = Deni::create([
            'pay_link_id'            => $payLink?->id,
            'creditor_name'          => $payLink ? null : $data['creditor_name'],
            'admin_token'            => Str::random(48),
            'debtor_token'           => Str::random(48),
            'debtor_phone_hash'      => $debtorHash,
            'lender_phone_encrypted' => Crypt::encryptString($lenderPhone),
            'description'            => $data['description'],
            'original_amount'        => $data['original_amount'],
            'due_date'               => $data['due_date'] ?? null,
        ]);

        $debtorUrl = url('/deni/' . $deni->debtor_token);
        $adminUrl  = url('/deni/admin/' . $deni->admin_token);

        $flash = [
            'deni_link'       => $debtorUrl,
            'deni_admin_link' => $adminUrl,
        ];

        if (isset($data['debtor_phone'])) {
            $waPhone   = preg_replace('/^(\+?254|0)/', '254', preg_replace('/\s/', '', $data['debtor_phone']));
            $waMessage = $creditorLabel
                . ' has recorded a deni of KES ' . number_format($data['original_amount'])
                . ' for: ' . $data['description']
                . '. View your balance and pay via M-Pesa: ' . $debtorUrl;
            $flash['deni_whatsapp'] = 'https://wa.me/' . $waPhone . '?text=' . rawurlencode($waMessage);
        }

        // Non-sellers go to their admin page (that's their "account" — they bookmark it)
        if (! $isSeller) {
            return redirect(url('/deni/admin/' . $deni->admin_token))->with($flash);
        }

        return back()->with($flash);
    }

    // Debtor's payment page
    public function show(string $token)
    {
        $deni = Deni::where('debtor_token', $token)->with('payLink')->firstOrFail();
        return view('deni.pay', compact('deni'));
    }

    // Debtor initiates STK Push
    public function pay(string $token, Request $request)
    {
        $deni = Deni::where('debtor_token', $token)->firstOrFail();

        if ($deni->status === 'settled') {
            return response()->json(['message' => 'This debt is fully settled.'], 422);
        }

        $data = $request->validate([
            'phone'  => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'amount' => ['required', 'integer', 'min:1', 'max:' . $deni->balance()],
        ]);

        if (! $deni->debtor_phone_hash) {
            $deni->update(['debtor_phone_hash' => $this->seller->hashPhone($data['phone'])]);
        }

        $result = $this->daraja->stkPush(
            phone: $data['phone'],
            amount: $data['amount'],
            accountRef: 'DENI-' . $deni->id,
            description: 'Tab: ' . mb_substr($deni->description, 0, 40),
        );

        if (! isset($result['CheckoutRequestID'])) {
            return response()->json(['message' => 'M-Pesa prompt failed. Try again.'], 422);
        }

        DeniPayment::create([
            'deni_id'             => $deni->id,
            'amount'              => $data['amount'],
            'checkout_request_id' => $result['CheckoutRequestID'],
        ]);

        return response()->json(['checkout_request_id' => $result['CheckoutRequestID']]);
    }

    // Poll payment status
    public function payStatus(string $token, Request $request)
    {
        $deni    = Deni::where('debtor_token', $token)->firstOrFail();
        $payment = DeniPayment::where('checkout_request_id', $request->query('checkout_request_id'))->first();

        if (! $payment || $payment->status === 'pending') {
            return response()->json(['status' => 'pending']);
        }

        $deni->refresh();
        return response()->json([
            'status'      => $payment->status,
            'amount_paid' => $deni->amount_paid,
            'balance'     => $deni->balance(),
            'deni_status' => $deni->status,
            'receipt'     => $payment->receipt_number,
        ]);
    }

    // Admin view — anyone with the admin_token can manage their deni
    public function adminView(string $token)
    {
        $deni = Deni::where('admin_token', $token)->with(['payLink', 'payments'])->firstOrFail();
        return view('deni.admin', compact('deni'));
    }
}
