<?php

namespace App\Http\Controllers;

use App\Models\Deni;
use App\Models\DeniPayment;
use App\Models\PayLink;
use App\Services\DarajaService;
use App\Services\SellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DeniController extends Controller
{
    public function __construct(
        private DarajaService $daraja,
        private SellerService $seller,
    ) {}

    // Seller creates a tab from dashboard
    public function store(Request $request)
    {
        $payLink = PayLink::findOrFail(session('seller_id'));

        $data = $request->validate([
            'description'     => ['required', 'string', 'max:300'],
            'original_amount' => ['required', 'integer', 'min:1', 'max:500000'],
            'debtor_phone'    => ['nullable', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'due_date'        => ['nullable', 'date', 'after:today'],
        ]);

        $debtorHash = isset($data['debtor_phone'])
            ? $this->seller->hashPhone($data['debtor_phone'])
            : null;

        $deni = Deni::create([
            'pay_link_id'       => $payLink->id,
            'admin_token'       => Str::random(48),
            'debtor_token'      => Str::random(48),
            'debtor_phone_hash' => $debtorHash,
            'description'       => $data['description'],
            'original_amount'   => $data['original_amount'],
            'due_date'          => $data['due_date'] ?? null,
        ]);

        return back()->with('deni_link', url('/deni/' . $deni->debtor_token));
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

    // Seller admin view (via admin_token)
    public function adminView(string $token)
    {
        $deni = Deni::where('admin_token', $token)->with(['payLink', 'payments'])->firstOrFail();
        return view('deni.admin', compact('deni'));
    }
}
