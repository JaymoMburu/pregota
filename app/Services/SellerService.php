<?php

namespace App\Services;

use App\Models\PayLink;
use App\Models\SellerPayment;
use Illuminate\Support\Facades\DB;

class SellerService
{
    public function __construct(private DarajaService $daraja) {}

    public function calculateFee(int $amount): array
    {
        $fee       = (int) max(1, ceil($amount * 0.01));
        $netAmount = $amount - $fee;

        return ['fee' => $fee, 'net_amount' => $netAmount];
    }

    public function initiate(int $amount, string $buyerPhone, PayLink $payLink, ?string $note = null): SellerPayment
    {
        $fees = $this->calculateFee($amount);

        return DB::transaction(function () use ($amount, $buyerPhone, $payLink, $note, $fees) {
            $payment = SellerPayment::create([
                'pay_link_id' => $payLink->id,
                'amount'      => $amount,
                'fee'         => $fees['fee'],
                'net_amount'  => $fees['net_amount'],
                'buyer_note'  => $note,
                'status'      => 'pending',
            ]);

            $stk = $this->daraja->stkPush(
                $amount,
                $buyerPhone,
                'PAY-' . $payment->id,
                'Pay ' . $payLink->business_name
            );

            if (isset($stk['CheckoutRequestID'])) {
                $payment->update(['mpesa_checkout_id' => $stk['CheckoutRequestID']]);
            }

            return $payment->fresh();
        });
    }

    public function confirmPayment(string $checkoutId, string $mpesaRef, float $amount): ?SellerPayment
    {
        $payment = SellerPayment::where('mpesa_checkout_id', $checkoutId)->first();
        if (! $payment) return null;

        $payment->update([
            'status'    => 'confirmed',
            'mpesa_ref' => $mpesaRef,
        ]);

        $link = $payment->payLink;
        $link->increment('total_received', $payment->net_amount);
        $link->increment('payment_count');

        return $payment->fresh();
    }

    public function failPayment(string $checkoutId): void
    {
        SellerPayment::where('mpesa_checkout_id', $checkoutId)
            ->where('status', 'pending')
            ->update(['status' => 'failed']);
    }
}
