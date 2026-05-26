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

    public function initiate(
        int $amount,
        string $buyerPhone,
        PayLink $payLink,
        ?string $note = null,
        int $tipAmount = 0,
        ?string $tipRecipient = null,
        ?string $tipComment = null
    ): SellerPayment {
        $fees  = $this->calculateFee($amount);
        $total = $amount + $tipAmount; // tip is fee-free, added on top

        return DB::transaction(function () use ($amount, $total, $buyerPhone, $payLink, $note, $fees, $tipAmount, $tipRecipient, $tipComment) {
            $payment = SellerPayment::create([
                'pay_link_id'   => $payLink->id,
                'amount'        => $amount,
                'fee'           => $fees['fee'],
                'net_amount'    => $fees['net_amount'],
                'tip_amount'    => $tipAmount,
                'tip_recipient' => $tipRecipient,
                'tip_comment'   => $tipComment,
                'buyer_note'    => $note,
                'status'        => 'pending',
            ]);

            $desc = 'Pay ' . $payLink->business_name;
            if ($tipAmount > 0) {
                $desc .= ' + Tip';
            }

            $stk = $this->daraja->stkPush(
                $total,
                $buyerPhone,
                'PAY-' . $payment->id,
                $desc
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
        $link->increment('total_received', $payment->net_amount + $payment->tip_amount);
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
