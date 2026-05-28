<?php

namespace App\Services;

use App\Models\LedgerEntry;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VoucherService
{
    public function __construct(private DarajaService $daraja) {}

    public function calculateFees(float $payoutAmount): array
    {
        $fee   = $this->tierFee($payoutAmount);
        $gross = (int) ceil($payoutAmount + $fee);

        return [
            'feeIn'     => $fee,
            'faceValue' => $payoutAmount,
            'feeOut'    => 0,
            'payout'    => $payoutAmount,
            'gross'     => $gross,
        ];
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

    public function initiate(float $payoutAmount, string $senderPhone, ?string $message, ?string $senderName = null): Voucher
    {
        $fees        = $this->calculateFees($payoutAmount);
        $grossAmount = $fees['gross'];

        return DB::transaction(function () use ($grossAmount, $payoutAmount, $senderPhone, $message, $senderName, $fees) {
            $voucher = Voucher::create([
                'code'         => Voucher::generateCode(),
                'gross_amount' => $grossAmount,
                'face_value'   => $fees['faceValue'],
                'fee_in'       => $fees['feeIn'],
                'fee_out'      => $fees['feeOut'],
                'payout_amount'=> $payoutAmount,
                'message'      => $message,
                'sender_name'  => $senderName,
                'status'       => 'pending',
                'expires_at'   => now()->addHours((int) config('pregota.voucher_expiry_hours')),
                'recall_token' => 'RC-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)),
            ]);

            // Hash chain — sender phone is NOT stored in ledger
            LedgerEntry::record($voucher, 'voucher_created', [
                'gross_amount' => $grossAmount,
                'face_value'   => $fees['faceValue'],
                'fee_in'       => $fees['feeIn'],
                'has_message'  => ! empty($message),
            ], $grossAmount);

            // STK Push — phone goes to Safaricom, not to our ledger
            $stk = $this->daraja->stkPush(
                $grossAmount,
                $senderPhone,
                $voucher->code,
                'Pregota Gift'
            );

            if (isset($stk['CheckoutRequestID'])) {
                $voucher->update(['mpesa_checkout_id' => $stk['CheckoutRequestID']]);

                LedgerEntry::record($voucher, 'stk_pushed', [
                    'merchant_request_id'  => $stk['MerchantRequestID'] ?? null,
                    'checkout_request_id'  => $stk['CheckoutRequestID'],
                    'response_code'        => $stk['ResponseCode'] ?? null,
                ]);
            }

            return $voucher->fresh();
        });
    }

    public function confirmDeposit(string $checkoutRequestId, string $mpesaCode, float $amount): ?Voucher
    {
        $voucher = Voucher::where('mpesa_checkout_id', $checkoutRequestId)->first();
        if (! $voucher) return null;

        $voucher->update([
            'status'                   => 'active',
            'mpesa_confirmation_code'  => $mpesaCode,
            'activated_at'             => now(),
        ]);

        LedgerEntry::record($voucher, 'stk_confirmed', [
            'mpesa_code' => $mpesaCode,
            'amount'     => $amount,
        ], $amount, $mpesaCode);

        LedgerEntry::record($voucher, 'voucher_activated', [
            'code'       => $voucher->code,
            'face_value' => $voucher->face_value,
        ]);

        return $voucher->fresh();
    }

    public function failDeposit(string $checkoutRequestId, string $reason): void
    {
        $voucher = Voucher::where('mpesa_checkout_id', $checkoutRequestId)->first();
        if (! $voucher) return;

        $voucher->update(['status' => 'cancelled']);

        LedgerEntry::record($voucher, 'stk_failed', ['reason' => $reason]);
    }

    public function claim(string $code, string $recipientPhone): array
    {
        $voucher = Voucher::where('code', strtoupper($code))->first();

        if (! $voucher) {
            return ['success' => false, 'message' => 'Invalid gift code. Please check and try again.'];
        }

        if ($voucher->isExpired()) {
            $voucher->update(['status' => 'expired']);
            return ['success' => false, 'message' => 'This gift code has expired.'];
        }

        // Hold window — sender has N minutes after activation to recall before recipient can claim
        $holdMinutes = (int) config('pregota.hold_minutes', 5);
        if ($voucher->status === 'active' && $voucher->activated_at && $voucher->activated_at->addMinutes($holdMinutes)->gt(now())) {
            $holdSeconds = max(0, (int) now()->diffInSeconds($voucher->activated_at->addMinutes($holdMinutes), false));
            return [
                'success'      => false,
                'in_hold'      => true,
                'hold_seconds' => $holdSeconds,
                'message'      => 'This gift has a short verification window. Please try again in ' . ceil($holdSeconds / 60) . ' minute(s).',
            ];
        }

        if (! $voucher->isActive()) {
            return match ($voucher->status) {
                'pending'   => ['success' => false, 'message' => 'This gift is still being processed. Please try again in a moment.'],
                'redeemed'  => ['success' => false, 'message' => 'This gift code has already been redeemed.'],
                'recalled'  => ['success' => false, 'message' => 'This gift has been recalled by the sender.'],
                'cancelled' => ['success' => false, 'message' => 'This gift code was cancelled.'],
                default     => ['success' => false, 'message' => 'This gift code is not valid.'],
            };
        }

        // Log the claim attempt — recipient phone NOT stored in ledger
        LedgerEntry::record($voucher, 'voucher_claimed', [
            'payout_amount' => $voucher->payout_amount,
            'fee_out'       => $voucher->fee_out,
        ], $voucher->payout_amount);

        // Manual payout mode — queue for admin to send manually
        if (config('pregota.manual_payouts', false)) {
            $voucher->update([
                'status'          => 'claimed',
                'claimed_at'      => now(),
                'recipient_phone' => $recipientPhone,
            ]);

            LedgerEntry::record($voucher, 'voucher_queued', [
                'payout_amount' => $voucher->payout_amount,
                'note'          => 'Manual payout mode — queued for admin',
            ], $voucher->payout_amount);

            return [
                'success'     => true,
                'manual'      => true,
                'message'     => 'Gift received! Your KES ' . number_format($voucher->payout_amount, 0) . ' will arrive on M-Pesa within a few hours.',
                'amount'      => $voucher->payout_amount,
                'gift_msg'    => $voucher->message,
                'sender_name' => $voucher->sender_name,
            ];
        }

        // Automatic B2C payout
        $b2c = $this->daraja->b2cPayout(
            (int) $voucher->payout_amount,
            $recipientPhone,
            'Pregota Gift ' . $voucher->code
        );

        if (isset($b2c['ConversationID'])) {
            $voucher->update([
                'status'               => 'redeemed',
                'redeemed_at'          => now(),
                'b2c_conversation_id'  => $b2c['ConversationID'],
            ]);

            LedgerEntry::record($voucher, 'b2c_initiated', [
                'conversation_id' => $b2c['ConversationID'],
                'payout_amount'   => $voucher->payout_amount,
            ], $voucher->payout_amount);

            return [
                'success'     => true,
                'message'     => 'Your gift is on its way! You will receive KES ' . number_format($voucher->payout_amount, 2) . ' shortly.',
                'amount'      => $voucher->payout_amount,
                'gift_msg'    => $voucher->message,
                'sender_name' => $voucher->sender_name,
            ];
        }

        return ['success' => false, 'message' => 'Payout failed. Please contact support with code: ' . $voucher->code];
    }

    public function recall(string $code, string $recallToken, string $senderPhone): array
    {
        $voucher = Voucher::where('code', strtoupper($code))->first();

        if (! $voucher) {
            return ['success' => false, 'message' => 'Gift code not found.'];
        }

        if (strtoupper($recallToken) !== strtoupper($voucher->recall_token)) {
            return ['success' => false, 'message' => 'Invalid recall token.'];
        }

        if ($voucher->status === 'redeemed') {
            return ['success' => false, 'message' => 'This gift has already been redeemed and cannot be recalled.'];
        }

        if ($voucher->status === 'recalled') {
            return ['success' => false, 'message' => 'This gift has already been recalled.'];
        }

        if (! in_array($voucher->status, ['active', 'pending'])) {
            return ['success' => false, 'message' => 'This gift cannot be recalled (status: ' . $voucher->status . ').'];
        }

        // Refund = face_value (gross minus deposit fee; payout fee is not charged since no B2C happened)
        $refundAmount = (int) $voucher->face_value;

        $b2c = $this->daraja->b2cPayout(
            $refundAmount,
            $senderPhone,
            'Pregota Refund ' . $voucher->code
        );

        if (! isset($b2c['ConversationID'])) {
            return ['success' => false, 'message' => 'Refund could not be initiated. Please try again or contact support.'];
        }

        $voucher->update([
            'status'      => 'recalled',
            'recalled_at' => now(),
        ]);

        LedgerEntry::record($voucher, 'voucher_recalled', [
            'refund_amount' => $refundAmount,
            'conversation_id' => $b2c['ConversationID'],
        ], $refundAmount);

        return [
            'success' => true,
            'message' => 'Gift recalled. KES ' . number_format($refundAmount, 2) . ' will be sent to your M-Pesa shortly.',
            'refund'  => $refundAmount,
        ];
    }

    public function expireStale(): int
    {
        return Voucher::where('status', 'active')
            ->where('expires_at', '<', now())
            ->each(function (Voucher $v) {
                $v->update(['status' => 'expired']);
                LedgerEntry::record($v, 'voucher_expired', ['reason' => 'ttl_exceeded']);
            })->count();
    }
}
