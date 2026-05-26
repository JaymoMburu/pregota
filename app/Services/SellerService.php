<?php

namespace App\Services;

use App\Models\BuyerStamp;
use App\Models\PayLink;
use App\Models\SellerPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    // Normalise Kenyan phone to 2547XXXXXXXX, then SHA-256 hash for storage.
    public function hashPhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);
        if (str_starts_with($digits, '0')) {
            $digits = '254' . substr($digits, 1);
        } elseif (str_starts_with($digits, '7') || str_starts_with($digits, '1')) {
            $digits = '254' . $digits;
        }
        return hash('sha256', $digits);
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
        $fees      = $this->calculateFee($amount);
        $total     = $amount + $tipAmount;
        $phoneHash = $this->hashPhone($buyerPhone);

        return DB::transaction(function () use ($amount, $total, $buyerPhone, $phoneHash, $payLink, $note, $fees, $tipAmount, $tipRecipient, $tipComment) {
            $payment = SellerPayment::create([
                'pay_link_id'      => $payLink->id,
                'amount'           => $amount,
                'fee'              => $fees['fee'],
                'net_amount'       => $fees['net_amount'],
                'tip_amount'       => $tipAmount,
                'tip_recipient'    => $tipRecipient,
                'tip_comment'      => $tipComment,
                'buyer_note'       => $note,
                'buyer_phone_hash' => $phoneHash,
                'status'           => 'pending',
            ]);

            $desc = 'Pay ' . $payLink->business_name;
            if ($tipAmount > 0) {
                $desc .= ' + Tip';
            }

            $stk = $this->daraja->stkPush($total, $buyerPhone, 'PAY-' . $payment->id, $desc);

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

        $receiptNumber = 'PRG-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        $payment->update([
            'status'         => 'confirmed',
            'mpesa_ref'      => $mpesaRef,
            'receipt_number' => $receiptNumber,
        ]);

        $link = $payment->payLink;
        $link->increment('total_received', $payment->net_amount + $payment->tip_amount);
        $link->increment('payment_count');

        // Upsert stamp card if the seller has stamps enabled and we have the buyer's phone hash
        if ($link->stamps_required && $payment->buyer_phone_hash) {
            $stamp = BuyerStamp::firstOrCreate(
                ['pay_link_id' => $link->id, 'phone_hash' => $payment->buyer_phone_hash],
                ['stamp_count' => 0, 'reward_pending' => false]
            );

            $newCount = $stamp->stamp_count + 1;
            $rewardPending = $newCount >= $link->stamps_required;

            // Reset count after reward threshold so the cycle restarts
            $stamp->update([
                'stamp_count'    => $rewardPending ? 0 : $newCount,
                'reward_pending' => $stamp->reward_pending || $rewardPending,
                'last_stamp_at'  => now(),
            ]);
        }

        return $payment->fresh();
    }

    public function failPayment(string $checkoutId): void
    {
        SellerPayment::where('mpesa_checkout_id', $checkoutId)
            ->where('status', 'pending')
            ->update(['status' => 'failed']);
    }

    // Returns stamp progress for a given phone + pay link, or null if stamps not enabled.
    public function stampInfo(PayLink $payLink, string $phone): ?array
    {
        if (! $payLink->stamps_required) return null;

        $hash  = $this->hashPhone($phone);
        $stamp = BuyerStamp::where('pay_link_id', $payLink->id)
                            ->where('phone_hash', $hash)
                            ->first();

        $count = $stamp?->stamp_count ?? 0;
        $pending = $stamp?->reward_pending ?? false;

        return [
            'stamp_count'     => $count,
            'stamps_required' => $payLink->stamps_required,
            'stamps_left'     => max(0, $payLink->stamps_required - $count),
            'reward_pending'  => $pending,
            'reward'          => $payLink->stamp_reward,
        ];
    }
}
