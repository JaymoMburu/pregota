<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LedgerEntry extends Model
{
    protected $fillable = [
        'voucher_id', 'event', 'payload', 'amount',
        'mpesa_ref', 'prev_hash', 'entry_hash', 'fabric_tx_id',
    ];

    protected $casts = [
        'payload' => 'array',
        'amount'  => 'decimal:2',
    ];

    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public static function record(Voucher $voucher, string $event, array $payload, ?float $amount = null, ?string $mpesaRef = null): static
    {
        $prevEntry = static::where('voucher_id', $voucher->id)->latest()->first();
        $prevHash  = $prevEntry?->entry_hash ?? 'GENESIS';

        $raw = implode('|', [
            $voucher->id,
            $event,
            json_encode($payload),
            $prevHash,
            now()->toISOString(),
        ]);

        $entryHash = hash('sha256', $raw);

        return static::create([
            'voucher_id' => $voucher->id,
            'event'      => $event,
            'payload'    => $payload,
            'amount'     => $amount,
            'mpesa_ref'  => $mpesaRef,
            'prev_hash'  => $prevHash,
            'entry_hash' => $entryHash,
        ]);
    }
}
