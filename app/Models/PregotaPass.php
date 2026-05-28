<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PregotaPass extends Model
{
    protected $fillable = [
        'phone_hash', 'pass_type', 'amount_paid',
        'checkout_request_id', 'receipt_number', 'status', 'expires_at',
    ];

    protected $casts = ['expires_at' => 'datetime'];

    public static function activeFor(string $phoneHash): ?self
    {
        return static::where('phone_hash', $phoneHash)
            ->where('status', 'active')
            ->where('expires_at', '>', now())
            ->latest('expires_at')
            ->first();
    }

    public function daysRemaining(): int
    {
        return max(0, (int) now()->diffInDays($this->expires_at, false));
    }
}
