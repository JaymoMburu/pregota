<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MultiGift extends Model
{
    protected $fillable = [
        'reference', 'items', 'total_payout', 'fee_in', 'fee_out_total',
        'gross_amount', 'status', 'mpesa_checkout_id', 'mpesa_confirmation_code',
    ];

    protected $casts = [
        'items' => 'array',
    ];

    public static function generateReference(): string
    {
        do {
            $ref = 'MULTI-' . strtoupper(Str::random(4)) . '-' . strtoupper(Str::random(4));
        } while (static::where('reference', $ref)->exists());

        return $ref;
    }

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isActive(): bool   { return $this->status === 'active'; }
    public function isComplete(): bool { return $this->status === 'complete'; }
    public function isFailed(): bool   { return $this->status === 'failed'; }

    public function distributedCount(): int
    {
        return collect($this->items)->filter(fn($i) => ($i['b2c_status'] ?? '') === 'sent')->count();
    }
}
