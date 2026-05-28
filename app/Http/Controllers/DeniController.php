<?php

namespace App\Http\Controllers;

use App\Models\Deni;
use App\Models\DeniItem;
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

    public function landing()
    {
        return view('deni.landing');
    }

    // Public creation page (no auth needed)
    public function create()
    {
        return view('deni.create');
    }

    // Anyone creates a tab — seller, creditor account, or anonymous
    public function store(Request $request)
    {
        $isSeller   = session()->has('seller_id');
        $isCreditor = session()->has('creditor_phone_hash');
        $payLink    = $isSeller ? PayLink::findOrFail(session('seller_id')) : null;

        $rules = [
            'description'     => ['required', 'string', 'max:300'],
            'original_amount' => ['required', 'integer', 'min:1', 'max:500000'],
            'debtor_name'     => ['nullable', 'string', 'max:100'],
            'debtor_phone'    => [$isCreditor ? 'required' : 'nullable', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'due_date'        => ['nullable', 'date', 'after:today'],
        ];

        if (! $isSeller && ! $isCreditor) {
            $rules['creditor_name'] = ['required', 'string', 'max:100'];
            $rules['payout_type']   = ['required', 'in:phone,till'];
            $rules['lender_phone']  = ['required_if:payout_type,phone', 'nullable', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'];
            $rules['lender_till']   = ['required_if:payout_type,till', 'nullable', 'string', 'regex:/^\d{5,7}$/'];
        }

        $data = $request->validate($rules);

        $debtorHash      = isset($data['debtor_phone']) ? $this->seller->hashPhone($data['debtor_phone']) : null;
        $debtorEncrypted = isset($data['debtor_phone']) ? Crypt::encryptString($data['debtor_phone']) : null;

        $lenderTill  = null;
        $lenderPhone = null;
        $lenderHash  = null;

        if ($isSeller) {
            $lenderPhone   = Crypt::decryptString($payLink->phone_encrypted);
            $creditorLabel = $payLink->business_name;
            $creditorName  = null;
        } elseif ($isCreditor) {
            $lenderPhone   = Crypt::decryptString(session('creditor_phone_encrypted'));
            $creditorLabel = session('creditor_name');
            $creditorName  = session('creditor_name');
        } else {
            $creditorLabel = $data['creditor_name'];
            $creditorName  = $data['creditor_name'];
            if (($data['payout_type'] ?? 'phone') === 'till') {
                $lenderTill = $data['lender_till'];
            } else {
                $lenderPhone = $data['lender_phone'];
            }
        }

        if ($lenderPhone) {
            $lenderHash = $this->seller->hashPhone($lenderPhone);
        }

        $deni = Deni::create([
            'pay_link_id'            => $payLink?->id,
            'creditor_name'          => $isSeller ? null : $creditorName,
            'admin_token'            => Str::random(48),
            'debtor_token'           => Str::random(48),
            'debtor_phone_hash'      => $debtorHash,
            'debtor_phone_encrypted' => $debtorEncrypted,
            'debtor_name'            => $data['debtor_name'] ?? null,
            'lender_phone_encrypted' => $lenderPhone ? Crypt::encryptString($lenderPhone) : null,
            'lender_phone_hash'      => $lenderHash,
            'lender_till'            => $lenderTill,
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

        if ($isCreditor) {
            return redirect()->route('creditor.dashboard')->with('charge_added', true);
        }

        if (! $isSeller) {
            return redirect(url('/deni/admin/' . $deni->admin_token))->with($flash);
        }

        return back()->with($flash);
    }

    // Debtor's payment page
    public function show(string $token, Request $request)
    {
        $deni = Deni::where('debtor_token', $token)->with(['payLink', 'items'])->firstOrFail();

        // No phone restriction — open to anyone
        if (! $deni->debtor_phone_hash) {
            return view('deni.pay', ['deni' => $deni, 'verified' => true]);
        }

        // Check session verification for this specific deni
        $sessionKey = 'deni_verified_' . $deni->id;
        if (session($sessionKey) === $deni->debtor_phone_hash) {
            return view('deni.pay', ['deni' => $deni, 'verified' => true]);
        }

        return view('deni.pay', ['deni' => $deni, 'verified' => false]);
    }

    // Verify debtor phone before showing deni
    public function verify(string $token, Request $request)
    {
        $deni = Deni::where('debtor_token', $token)->firstOrFail();

        $data = $request->validate([
            'phone' => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
        ]);

        $hash = $this->seller->hashPhone($data['phone']);

        if ($hash !== $deni->debtor_phone_hash) {
            return response()->json(['match' => false]);
        }

        session(['deni_verified_' . $deni->id => $hash]);
        return response()->json(['match' => true]);
    }

    // Debtor initiates STK Push
    public function pay(string $token, Request $request)
    {
        $deni = Deni::where('debtor_token', $token)->firstOrFail();

        if ($deni->debtor_phone_hash && session('deni_verified_' . $deni->id) !== $deni->debtor_phone_hash) {
            return response()->json(['message' => 'Please verify your number first.'], 403);
        }

        if ($deni->status === 'settled') {
            return response()->json(['message' => 'This debt is fully settled.'], 422);
        }

        $data = $request->validate([
            'phone'  => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'amount' => ['required', 'integer', 'min:1', 'max:' . $deni->balance()],
        ]);

        $faceValue = (int) $data['amount'];
        $fee       = $this->deniTierFee($faceValue);
        $total     = $faceValue + $fee;

        if (! $deni->debtor_phone_hash) {
            $deni->update(['debtor_phone_hash' => $this->seller->hashPhone($data['phone'])]);
        }

        $result = $this->daraja->stkPush(
            phone: $data['phone'],
            amount: $total,
            accountRef: 'DENI-' . $deni->id,
            description: 'Tab: ' . mb_substr($deni->description, 0, 40),
        );

        if (! isset($result['CheckoutRequestID'])) {
            return response()->json(['message' => 'M-Pesa prompt failed. Try again.'], 422);
        }

        DeniPayment::create([
            'deni_id'             => $deni->id,
            'amount'              => $total,
            'face_value'          => $faceValue,
            'fee'                 => $fee,
            'checkout_request_id' => $result['CheckoutRequestID'],
        ]);

        return response()->json([
            'checkout_request_id' => $result['CheckoutRequestID'],
            'face_value'          => $faceValue,
            'fee'                 => $fee,
            'total'               => $total,
        ]);
    }

    private function deniTierFee(int $amount): int
    {
        foreach (config('pregota.deni_tiers') as $tier) {
            if ($amount >= $tier['min'] && ($tier['max'] === null || $amount <= $tier['max'])) {
                return $tier['type'] === 'flat'
                    ? (int) $tier['value']
                    : (int) ceil($amount * $tier['value'] / 100);
            }
        }
        return 0;
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
        $deni = Deni::where('admin_token', $token)->with(['payLink', 'payments', 'items'])->firstOrFail();
        return view('deni.admin', compact('deni'));
    }

    // Creditor quick-add: create deni for a known customer (phone already stored)
    public function quickStore(Request $request)
    {
        $isCreditor = session()->has('creditor_phone_hash');
        $isSeller   = session()->has('seller_id');

        if (! $isCreditor && ! $isSeller) {
            return response()->json(['message' => 'Unauthorised'], 403);
        }

        $data = $request->validate([
            'debtor_phone_hash'      => ['required', 'string', 'size:64'],
            'debtor_phone_encrypted' => ['required', 'string'],
            'debtor_name'            => ['nullable', 'string', 'max:100'],
            'description'            => ['required', 'string', 'max:300'],
            'original_amount'        => ['required', 'integer', 'min:1', 'max:500000'],
            'due_date'               => ['nullable', 'date', 'after:today'],
        ]);

        if ($isCreditor) {
            $lenderPhone  = Crypt::decryptString(session('creditor_phone_encrypted'));
            $creditorName = session('creditor_name');
            $payLink      = null;
        } else {
            $payLink     = PayLink::findOrFail(session('seller_id'));
            $lenderPhone = Crypt::decryptString($payLink->phone_encrypted);
            $creditorName = null;
        }

        $lenderHash = $this->seller->hashPhone($lenderPhone);

        $deni = Deni::create([
            'pay_link_id'            => $payLink?->id,
            'creditor_name'          => $creditorName,
            'admin_token'            => Str::random(48),
            'debtor_token'           => Str::random(48),
            'debtor_phone_hash'      => $data['debtor_phone_hash'],
            'debtor_phone_encrypted' => $data['debtor_phone_encrypted'],
            'debtor_name'            => $data['debtor_name'] ?? null,
            'lender_phone_encrypted' => Crypt::encryptString($lenderPhone),
            'lender_phone_hash'      => $lenderHash,
            'description'            => $data['description'],
            'original_amount'        => $data['original_amount'],
            'due_date'               => $data['due_date'] ?? null,
        ]);

        return response()->json([
            'success'    => true,
            'deni_id'    => $deni->id,
            'pay_link'   => url('/deni/' . $deni->debtor_token),
            'admin_link' => url('/deni/admin/' . $deni->admin_token),
            'balance'    => $deni->balance(),
        ]);
    }

    // Add a charge to an existing open tab
    public function addCharge(Request $request, string $token)
    {
        $deni = Deni::where('admin_token', $token)->firstOrFail();

        $data = $request->validate([
            'description' => ['required', 'string', 'max:200'],
            'amount'      => ['required', 'integer', 'min:1', 'max:500000'],
        ]);

        $deni->items()->create($data);
        $deni->increment('original_amount', $data['amount']);

        if ($deni->status === 'settled') {
            $deni->update(['status' => 'partial']);
        }

        $from = $request->input('from', 'admin');
        if ($from === 'dashboard') {
            return redirect()->route('seller.dashboard')->with('charge_added', $deni->description);
        }
        if ($from === 'creditor') {
            return redirect()->route('creditor.dashboard')->with('charge_added', true);
        }
        return redirect()->route('deni.admin', $token)->with('charge_added', true);
    }
}
