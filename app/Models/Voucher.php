<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Voucher extends Model
{
    protected $fillable = [
        'bulk_gift_id',
        'code', 'face_value', 'gross_amount', 'fee_in', 'fee_out', 'payout_amount',
        'message', 'sender_name', 'status',
        'mpesa_checkout_id', 'mpesa_confirmation_code',
        'b2c_conversation_id', 'b2c_confirmation_code',
        'prev_hash', 'entry_hash',
        'activated_at', 'redeemed_at', 'expires_at',
        'recipient_phone', 'claimed_at',
    ];

    protected $casts = [
        'face_value'    => 'decimal:2',
        'gross_amount'  => 'decimal:2',
        'fee_in'        => 'decimal:2',
        'fee_out'       => 'decimal:2',
        'payout_amount' => 'decimal:2',
        'activated_at'  => 'datetime',
        'redeemed_at'   => 'datetime',
        'expires_at'    => 'datetime',
        'claimed_at'    => 'datetime',
    ];

    public function bulkGift(): BelongsTo
    {
        return $this->belongsTo(BulkGift::class);
    }

    public function ledger(): HasMany
    {
        return $this->hasMany(LedgerEntry::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast()
            && $this->status !== 'redeemed';
    }

    public static function generateCode(): string
    {
        do {
            $part1 = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
            $part2 = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
            $code  = "PRG-{$part1}-{$part2}";
        } while (static::where('code', $code)->exists());

        return $code;
    }

    public function computeHash(?string $prevHash): string
    {
        $data = implode('|', [
            $this->id,
            $this->code,
            $this->gross_amount,
            $this->face_value,
            $this->status,
            $prevHash ?? 'GENESIS',
            $this->created_at?->toISOString() ?? now()->toISOString(),
        ]);

        return hash('sha256', $data);
    }
}
