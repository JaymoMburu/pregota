<?php

namespace App\Services;

use App\Models\BulkGift;
use App\Models\Voucher;
use Illuminate\Support\Str;

class BulkGiftService
{
    public function __construct(
        private VoucherService $voucherService,
        private DarajaService  $daraja,
    ) {}

    public function calculateFees(int $count, float $amountPerCode): array
    {
        $perCode     = $this->voucherService->calculateFees($amountPerCode);
        $grossTotal  = (int) ceil($perCode['gross'] * $count);
        $feeInTotal  = round($perCode['feeIn']  * $count, 2);
        $feeOutTotal = round($perCode['feeOut'] * $count, 2);
        $totalPayout = round($amountPerCode      * $count, 2);

        return [
            'perCode'     => $perCode,
            'grossTotal'  => $grossTotal,
            'feeInTotal'  => $feeInTotal,
            'feeOutTotal' => $feeOutTotal,
            'totalPayout' => $totalPayout,
        ];
    }

    public function initiate(string $companyName, string $contactName, int $count, float $amountPerCode, string $senderPhone): BulkGift
    {
        $fees = $this->calculateFees($count, $amountPerCode);
        $perCode = $fees['perCode'];

        $bulk = BulkGift::create([
            'reference'    => BulkGift::generateReference(),
            'company_name' => $companyName,
            'contact_name' => $contactName,
            'amount_per_code' => $amountPerCode,
            'code_count'   => $count,
            'total_payout' => $fees['totalPayout'],
            'fee_in_total' => $fees['feeInTotal'],
            'fee_out_total'=> $fees['feeOutTotal'],
            'gross_amount' => $fees['grossTotal'],
            'status'       => 'pending',
        ]);

        $stk = $this->daraja->stkPush(
            $fees['grossTotal'],
            $senderPhone,
            $bulk->reference,
            'Pregota Bulk Gift Codes'
        );

        if (isset($stk['CheckoutRequestID'])) {
            $bulk->update(['mpesa_checkout_id' => $stk['CheckoutRequestID']]);
        }

        return $bulk->fresh();
    }

    public function confirmPayment(string $checkoutId, string $mpesaCode, float $amount): ?BulkGift
    {
        $bulk = BulkGift::where('mpesa_checkout_id', $checkoutId)->where('status', 'pending')->first();
        if (! $bulk) return null;

        $bulk->update(['status' => 'active', 'mpesa_confirmation_code' => $mpesaCode]);

        $this->issueVouchers($bulk);

        return $bulk->fresh();
    }

    public function failPayment(string $checkoutId): void
    {
        BulkGift::where('mpesa_checkout_id', $checkoutId)
            ->where('status', 'pending')
            ->update(['status' => 'failed']);
    }

    private function issueVouchers(BulkGift $bulk): void
    {
        $perCode = $this->voucherService->calculateFees((float) $bulk->amount_per_code);
        $expiry  = now()->addHours((int) config('pregota.voucher_expiry_hours'));

        for ($i = 0; $i < $bulk->code_count; $i++) {
            Voucher::create([
                'bulk_gift_id'  => $bulk->id,
                'code'          => Voucher::generateCode(),
                'gross_amount'  => $perCode['gross'],
                'face_value'    => $perCode['faceValue'],
                'fee_in'        => $perCode['feeIn'],
                'fee_out'       => $perCode['feeOut'],
                'payout_amount' => $perCode['payout'],
                'sender_name'   => $bulk->company_name,
                'status'        => 'active',
                'activated_at'  => now(),
                'expires_at'    => $expiry,
                'recall_token'  => 'RC-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4)),
                'mpesa_confirmation_code' => $bulk->mpesa_confirmation_code,
            ]);
        }
    }
}
