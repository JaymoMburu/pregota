<?php

namespace App\Services;

use App\Models\DirectGift;
use Illuminate\Support\Facades\DB;

class DirectGiftService
{
    public function __construct(private DarajaService $daraja) {}

    public function calculateFees(float $giftAmount): array
    {
        $fee   = (int) config('pregota.gift_direct_fee', 75);
        $gross = (int) ceil($giftAmount + $fee);

        return ['fee' => $fee, 'giftAmount' => $giftAmount, 'gross' => $gross];
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
