<?php

namespace App\Services;

use App\Models\BillSplit;
use App\Models\BillSplitPayment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BillSplitService
{
    public function __construct(private DarajaService $daraja) {}

    public function create(array $data): BillSplit
    {
        $bill = new BillSplit([
            'waiter_token'  => Str::random(32),
            'split_token'   => Str::random(16),
            'business_name' => $data['business_name'],
            'label'         => $data['label'] ?? null,
            'tip_handle'    => $data['tip_handle'] ?? null,
            'total_amount'  => (int) $data['total_amount'],
            'status'        => 'open',
            'expires_at'    => now()->addMinutes(90),
        ]);

        $bill->setPayoutDestination($data['payout_destination'], $data['payout_type']);
        $bill->save();

        return $bill;
    }

    public function pay(BillSplit $bill, int $requestedAmount, string $phone): BillSplitPayment
    {
        $fee = (int) config('pregota.collection_fee', 30);

        return DB::transaction(function () use ($bill, $requestedAmount, $phone, $fee) {
            $bill = BillSplit::lockForUpdate()->find($bill->id);

            $amount = min($requestedAmount, $bill->remainingAmount());

            if ($amount <= 0) {
                throw new \RuntimeException('Bill is already fully paid.');
            }

            $gross = $amount + $fee;

            $payment = BillSplitPayment::create([
                'bill_split_id' => $bill->id,
                'amount'        => $amount,
                'fee'           => $fee,
                'gross_amount'  => $gross,
                'status'        => 'pending',
            ]);

            $desc = $bill->business_name . ($bill->label ? ' · ' . $bill->label : '');
            $stk  = $this->daraja->stkPush($gross, $phone, 'SPLIT-' . $payment->id, $desc);

            if (isset($stk['CheckoutRequestID'])) {
                $payment->update(['mpesa_checkout_id' => $stk['CheckoutRequestID']]);
            }

            return $payment->fresh();
        });
    }

    public function confirmPayment(string $checkoutId, string $mpesaCode): ?BillSplitPayment
    {
        $payment = BillSplitPayment::where('mpesa_checkout_id', $checkoutId)->first();
        if (! $payment) return null;

        $payment->update([
            'status'                  => 'paid',
            'mpesa_confirmation_code' => $mpesaCode,
        ]);

        $bill = $payment->billSplit;

        DB::transaction(function () use ($bill, $payment) {
            $bill = BillSplit::lockForUpdate()->find($bill->id);
            $bill->increment('paid_amount', $payment->amount);
        });

        $bill->refresh();

        if ($bill->paid_amount >= $bill->total_amount && $bill->status === 'open') {
            $this->payout($bill);
        }

        return $payment->fresh();
    }

    public function payout(BillSplit $bill): bool
    {
        $bill->update(['status' => 'settling']);

        $destination = $bill->getPayoutDestination();
        $bill->update(['payout_phone_encrypted' => null]);

        $accountRef   = $bill->label ? substr($bill->label, 0, 12) : 'SPLIT';
        $paymentCount = $bill->payments()->where('status', 'paid')->count();
        $remarks      = trim(sprintf(
            '%s | %d pmt%s | %s',
            $bill->label ?: $bill->business_name,
            $paymentCount,
            $paymentCount === 1 ? '' : 's',
            now()->format('d/m H:i')
        ));

        $result = $this->daraja->b2bPayout(
            $bill->total_amount,
            $destination,
            $bill->payout_type,
            $accountRef,
            $remarks
        );

        if (isset($result['ConversationID'])) {
            $bill->update([
                'status'              => 'settled',
                'b2c_conversation_id' => $result['ConversationID'],
                'settled_at'          => now(),
            ]);
            return true;
        }

        $bill->update(['status' => 'open']);
        return false;
    }

    public function failPayment(string $checkoutId): void
    {
        BillSplitPayment::where('mpesa_checkout_id', $checkoutId)
            ->where('status', 'pending')
            ->update(['status' => 'failed']);
    }
}
