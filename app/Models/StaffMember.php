<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;

class StaffMember extends Model
{
    protected $fillable = [
        'business_id', 'handle', 'name', 'role', 'branch',
        'avatar_emoji', 'payout_phone_encrypted', 'alert_token',
        'active', 'total_received',
        'login_phone', 'password', 'is_solo',
        'till_encrypted', 'till_type',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'active'  => 'boolean',
        'is_solo' => 'boolean',
    ];

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function tips()
    {
        return $this->hasMany(TipTransaction::class);
    }

    public function feedback()
    {
        return $this->hasMany(TipFeedback::class);
    }

    public function getPayoutPhone(): string
    {
        return Crypt::decryptString($this->payout_phone_encrypted);
    }

    public function setPayoutPhone(string $phone): void
    {
        $this->payout_phone_encrypted = Crypt::encryptString($phone);
    }

    public function setTill(string $number, string $type): void
    {
        $this->till_encrypted = Crypt::encryptString($number);
        $this->till_type      = $type;
    }

    public function getTill(): ?string
    {
        return $this->till_encrypted ? Crypt::decryptString($this->till_encrypted) : null;
    }

    public function hasTill(): bool
    {
        return (bool) $this->till_encrypted;
    }

    public function averageRating(): float
    {
        return round($this->feedback()->avg('rating') ?? 0, 1);
    }

    public function todayTips(): float
    {
        return $this->tips()
            ->where('status', 'paid')
            ->whereDate('paid_at', today())
            ->sum('tip_amount');
    }

    public function monthTips(): float
    {
        return $this->tips()
            ->where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('tip_amount');
    }

    public function tipCount(): int
    {
        return $this->tips()->where('status', 'paid')->count();
    }
}
