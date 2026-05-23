<?php

namespace App\Services;

use App\Models\Creator;
use App\Models\MultiGift;
use Illuminate\Support\Facades\Log;

class MultiGiftService
{
    // KES 30 per-creator B2C buffer (covers Safaricom B2C for amounts ≤ KES 1,500)
    private const FEE_PER_CREATOR = 30;

    public function __construct(private DarajaService $daraja) {}

    public function calculateFees(array $items): array
    {
        $totalPayout = array_sum(array_column($items, 'amount'));
        $creatorCount = count($items);

        $feeInPct  = (float) config('pregota.fee_in_pct', 2.5);
        $feeMin    = (float) config('pregota.fee_min_kes', 50);

        $feeIn       = (int) ceil(max($feeMin, $totalPayout * $feeInPct / (100 - $feeInPct)));
        $feeOutTotal = self::FEE_PER_CREATOR * $creatorCount;
        $gross       = $totalPayout + $feeIn + $feeOutTotal;

        return compact('totalPayout', 'feeIn', 'feeOutTotal', 'gross');
    }

    public function initiate(array $validatedItems, string $senderPhone): MultiGift
    {
        $fees = $this->calculateFees($validatedItems);

        $items = array_map(fn($i) => [
            'creator_id'   => $i['creator_id'],
            'handle'       => $i['handle'],
            'display_name' => $i['display_name'],
            'amount'       => (int) $i['amount'],
            'b2c_conv_id'  => null,
            'b2c_status'   => 'pending',
        ], $validatedItems);

        $gift = MultiGift::create([
            'reference'     => MultiGift::generateReference(),
            'items'         => $items,
            'total_payout'  => $fees['totalPayout'],
            'fee_in'        => $fees['feeIn'],
            'fee_out_total' => $fees['feeOutTotal'],
            'gross_amount'  => $fees['gross'],
            'status'        => 'pending',
        ]);

        $stk = $this->daraja->stkPush(
            $fees['gross'],
            $senderPhone,
            $gift->reference,
            'Pregota — Multi-Creator Gift'
        );

        if (isset($stk['CheckoutRequestID'])) {
            $gift->update(['mpesa_checkout_id' => $stk['CheckoutRequestID']]);
        }

        return $gift->fresh();
    }

    public function confirmPayment(string $checkoutId, string $mpesaCode, float $amount): ?MultiGift
    {
        $gift = MultiGift::where('mpesa_checkout_id', $checkoutId)->where('status', 'pending')->first();
        if (! $gift) return null;

        $gift->update(['status' => 'active', 'mpesa_confirmation_code' => $mpesaCode]);

        $this->distributeAll($gift);

        return $gift->fresh();
    }

    public function failPayment(string $checkoutId): void
    {
        MultiGift::where('mpesa_checkout_id', $checkoutId)
            ->where('status', 'pending')
            ->update(['status' => 'failed']);
    }

    public function distributeAll(MultiGift $gift): void
    {
        $gift->update(['status' => 'distributing']);
        $items = $gift->items;

        foreach ($items as $idx => $item) {
            $creator = Creator::find($item['creator_id']);
            if (! $creator) {
                $items[$idx]['b2c_status'] = 'failed';
                continue;
            }

            try {
                $phone = $creator->getPayoutPhone();
                $b2c   = $this->daraja->b2cPayout(
                    $item['amount'],
                    $phone,
                    'Pregota Gift from Fan'
                );

                $items[$idx]['b2c_conv_id'] = $b2c['ConversationID'] ?? null;
                $items[$idx]['b2c_status']  = isset($b2c['ConversationID']) ? 'sent' : 'failed';
            } catch (\Exception $e) {
                Log::error('MultiGift B2C failed', ['gift_id' => $gift->id, 'creator' => $item['handle'], 'error' => $e->getMessage()]);
                $items[$idx]['b2c_status'] = 'failed';
            }
        }

        $allSent  = collect($items)->every(fn($i) => $i['b2c_status'] === 'sent');
        $anyFailed = collect($items)->contains(fn($i) => $i['b2c_status'] === 'failed');

        $gift->update([
            'items'  => $items,
            'status' => $allSent ? 'complete' : ($anyFailed ? 'failed' : 'distributing'),
        ]);
    }
}
