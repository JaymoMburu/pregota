<?php

namespace App\Services;

use App\Models\StaffMember;
use App\Models\TipTransaction;
use Illuminate\Support\Facades\DB;

class TipService
{
    public function __construct(private DarajaService $daraja) {}

    public function calculateFees(float $tipAmount, bool $feeWaived = false): array
    {
        $flat  = $feeWaived ? 0 : (int) config('pregota.tip_fee_flat', 15);
        $gross = (int) ceil($tipAmount + $flat);

        return [
            'feeIn'     => $flat,
            'feeOut'    => 0,
            'faceValue' => $tipAmount,
            'gross'     => $gross,
        ];
    }

    public function initiate(float $tipAmount, string $senderPhone, StaffMember $staff): TipTransaction
    {
        $staff->loadMissing('business');
        $feeWaived = $staff->business && $staff->business->isSubscribed();
        $fees = $this->calculateFees($tipAmount, $feeWaived);

        return DB::transaction(function () use ($tipAmount, $senderPhone, $staff, $fees) {
            $tip = TipTransaction::create([
                'staff_member_id' => $staff->id,
                'gross_amount'    => $fees['gross'],
                'tip_amount'      => $tipAmount,
                'fee_in'          => $fees['feeIn'],
                'fee_out'         => $fees['feeOut'],
                'status'          => 'pending',
            ]);

            $stk = $this->daraja->stkPush(
                $fees['gross'],
                $senderPhone,
                'TIP-' . $tip->id,
                'Pregota Tip — ' . $staff->name
            );

            if (isset($stk['CheckoutRequestID'])) {
                $tip->update(['mpesa_checkout_id' => $stk['CheckoutRequestID']]);
            }

            return $tip->fresh();
        });
    }

    public function confirmPayment(string $checkoutRequestId, string $mpesaCode, float $amount): ?TipTransaction
    {
        $tip = TipTransaction::where('mpesa_checkout_id', $checkoutRequestId)->first();
        if (! $tip) return null;

        $tip->update([
            'status'                  => 'active',
            'mpesa_confirmation_code' => $mpesaCode,
            'activated_at'            => now(),
        ]);

        $staff = $tip->staff;
        $b2c   = $this->daraja->b2cPayout(
            (int) $tip->tip_amount,
            $staff->getPayoutPhone(),
            'Pregota Tip ' . $tip->id
        );

        if (isset($b2c['ConversationID'])) {
            $tip->update([
                'status'             => 'paid',
                'b2c_conversation_id'=> $b2c['ConversationID'],
                'paid_at'            => now(),
            ]);

            $staff->increment('total_received', $tip->tip_amount);
        }

        return $tip->fresh();
    }

    public function failPayment(string $checkoutRequestId): void
    {
        TipTransaction::where('mpesa_checkout_id', $checkoutRequestId)
            ->where('status', 'pending')
            ->update(['status' => 'failed']);
    }
}
