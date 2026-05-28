<?php

namespace App\Services;

use App\Models\DirectGift;
use Illuminate\Support\Facades\DB;

class DirectGiftService
{
    public function __construct(private DarajaService $daraja) {}

    public function calculateFees(float $giftAmount): array
    {
        $fee   = $this->tierFee($giftAmount);
        $gross = (int) ceil($giftAmount + $fee);

        return ['fee' => $fee, 'giftAmount' => $giftAmount, 'gross' => $gross];
    }

    private function tierFee(float $amount): float
    {
        foreach (config('pregota.gift_tiers') as $tier) {
            if ($amount >= $tier['min'] && $amount <= $tier['max']) {
                return $tier['type'] === 'flat'
                    ? (float) $tier['value']
                    : round($amount * $tier['value'] / 100, 2);
            }
        }
        $tiers = config('pregota.gift_tiers');
        $last  = end($tiers);
        return round($amount * $last['value'] / 100, 2);
    }

    public function initiate(float $giftAmount, string $senderPhone, string $recipientPhone): DirectGift
    {
        $fees = $this->calculateFees($giftAmount);

        return DB::transaction(function () use ($giftAmount, $senderPhone, $recipientPhone, $fees) {
            $gift = new DirectGift([
                'gross_amount' => $fees['gross'],
                'gift_amount'  => $giftAmount,
                'fee'          => $fees['fee'],
                'status'       => 'pending',
            ]);
            $gift->setRecipientPhone($recipientPhone);
            $gift->save();

            $stk = $this->daraja->stkPush(
                $fees['gross'],
                $senderPhone,
                'DGIFT-' . $gift->id,
                'Pregota Direct Gift'
            );

            if (isset($stk['CheckoutRequestID'])) {
                $gift->update(['mpesa_checkout_id' => $stk['CheckoutRequestID']]);
            }

            return $gift->fresh();
        });
    }

    public function confirmPayment(string $checkoutRequestId, string $mpesaCode, float $amount): ?DirectGift
    {
        $gift = DirectGift::where('mpesa_checkout_id', $checkoutRequestId)->first();
        if (! $gift) return null;

        $gift->update(['mpesa_confirmation_code' => $mpesaCode]);

        // Read and immediately destroy the recipient phone
        $recipientPhone = $gift->getRecipientPhone();
        $gift->update(['recipient_phone_encrypted' => null]);

        $b2c = $this->daraja->b2cPayout(
            (int) $gift->gift_amount,
            $recipientPhone,
            'DirectGift-' . $gift->id
        );

        if (isset($b2c['ConversationID'])) {
            $gift->update([
                'status'             => 'paid',
                'b2c_conversation_id'=> $b2c['ConversationID'],
                'paid_at'            => now(),
            ]);
        } else {
            $gift->update(['status' => 'failed']);
        }

        return $gift->fresh();
    }

    public function failPayment(string $checkoutRequestId): void
    {
        DirectGift::where('mpesa_checkout_id', $checkoutRequestId)
            ->where('status', 'pending')
            ->update(['status' => 'failed', 'recipient_phone_encrypted' => null]);
    }
}
