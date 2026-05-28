<?php

namespace App\Http\Controllers;

use App\Models\PregotaPass;
use App\Services\DarajaService;
use App\Services\SellerService;
use Illuminate\Http\Request;

class PassController extends Controller
{
    public function __construct(
        private DarajaService $daraja,
        private SellerService $seller,
    ) {}

    public function buyPage()
    {
        $passes      = config('pregota.passes');
        $phoneHash   = session('me_verified') ?? session('creditor_phone_hash') ?? session('seller_phone_hash');
        $activePass  = $phoneHash ? PregotaPass::activeFor($phoneHash) : null;

        return view('pass.buy', compact('passes', 'activePass'));
    }

    public function purchase(Request $request)
    {
        $passes = config('pregota.passes');

        $data = $request->validate([
            'phone'     => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'pass_type' => ['required', 'string', 'in:' . implode(',', array_keys($passes))],
        ]);

        $passConfig = $passes[$data['pass_type']];
        $hash       = $this->seller->hashPhone($data['phone']);

        $existing = PregotaPass::activeFor($hash);
        if ($existing) {
            return response()->json([
                'already_active' => true,
                'pass_type'      => $existing->pass_type,
                'expires_at'     => $existing->expires_at->format('d M Y H:i'),
            ]);
        }

        $stk = $this->daraja->stkPush(
            phone: $data['phone'],
            amount: $passConfig['price'],
            accountRef: 'PASS-' . strtoupper($data['pass_type']),
            description: 'Pregota ' . $passConfig['label'],
        );

        if (! isset($stk['CheckoutRequestID'])) {
            return response()->json(['message' => 'M-Pesa prompt failed. Try again.'], 422);
        }

        PregotaPass::create([
            'phone_hash'          => $hash,
            'pass_type'           => $data['pass_type'],
            'amount_paid'         => $passConfig['price'],
            'checkout_request_id' => $stk['CheckoutRequestID'],
        ]);

        return response()->json(['checkout_request_id' => $stk['CheckoutRequestID']]);
    }

    public function poll(Request $request)
    {
        $pass = PregotaPass::where('checkout_request_id', $request->query('checkout_request_id'))->first();

        if (! $pass) return response()->json(['status' => 'not_found']);

        if ($pass->status === 'active') {
            return response()->json([
                'status'     => 'active',
                'pass_type'  => $pass->pass_type,
                'expires_at' => $pass->expires_at->format('d M Y H:i'),
            ]);
        }

        if ($pass->status === 'failed') {
            return response()->json(['status' => 'failed']);
        }

        return response()->json(['status' => 'pending']);
    }
}
