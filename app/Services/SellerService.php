<?php

namespace App\Services;

use App\Models\PayLink;
use App\Models\SellerPayment;
use Illuminate\Support\Facades\DB;

class SellerService
{
    // Minimum accumulated net balance before a payout is triggered.
    // Below this, B2C cost would eat into Pregota's fee on small-volume days.
    // At KES 500+: B2C costs ~KES 13, fees collected ~KES 15+ → profitable.
    public const MIN_PAYOUT_KES = 500;

    public function __construct(private DarajaService $daraja) {}

    public function calculateFee(int $amount): array
    {
        $fee       = (int) max(2, ceil($amount * 0.01));
        $netAmount = $amount - $fee;

        return ['fee' => $fee, 'net_amount' => $netAmount];
    }

    public function isPayoutReady(PayLink $payLink): bool
    {
        return $payLink->total_received >= self::MIN_PAYOUT_KES;
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

        $receiptNumber = 'PRG-' . now()->format('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(6));

        $payment->update([
            'status'         => 'confirmed',
            'mpesa_ref'      => $mpesaRef,
            'receipt_number' => $receiptNumber,
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
