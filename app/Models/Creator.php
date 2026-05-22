<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

class Creator extends Model
{
    protected $fillable = [
        'handle', 'display_name', 'bio', 'photo_url',
        'payout_phone_encrypted', 'password',
        'goal_title', 'goal_amount', 'min_gift_amount',
        'total_received', 'is_active', 'is_verified', 'alert_token',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_verified' => 'boolean',
    ];

    protected $hidden = ['password', 'payout_phone_encrypted'];

    public static function generateAlertToken(): string
    {
        return Str::random(64);
    }

    public function setPayoutPhone(string $phone): void
    {
        $this->update(['payout_phone_encrypted' => Crypt::encryptString($phone)]);
    }

    public function getPayoutPhone(): string
    {
        return Crypt::decryptString($this->payout_phone_encrypted);
    }

    public function gifts()
    {
        return $this->hasMany(CreatorGift::class);
    }

    public function goalProgress(): int
    {
        if (! $this->goal_amount || $this->goal_amount <= 0) return 0;
        return (int) min(100, round($this->total_received / $this->goal_amount * 100));
    }
}
