<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class BillSplit extends Model
{
    protected $fillable = [
        'waiter_token', 'split_token', 'business_name', 'label', 'tip_handle',
        'total_amount', 'paid_amount',
        'payout_phone_encrypted', 'payout_type', 'b2c_conversation_id',
        'status', 'expires_at', 'settled_at',
    ];

    protected $casts = [
        'expires_at'  => 'datetime',
        'settled_at'  => 'datetime',
    ];

    public function payments()
    {
        return $this->hasMany(BillSplitPayment::class);
    }

    public function remainingAmount(): int
    {
        return max(0, $this->total_amount - $this->paid_amount);
    }

    public function progressPct(): int
    {
        if ($this->total_amount <= 0) return 0;
        return (int) min(100, round($this->paid_amount / $this->total_amount * 100));
    }

    public function isOpen(): bool
    {
        return $this->status === 'open' && $this->expires_at->isFuture();
    }

    public function setPayoutDestination(string $destination, string $type): void
    {
        $this->payout_phone_encrypted = Crypt::encryptString($destination);
        $this->payout_type = $type;
    }

    public function getPayoutDestination(): string
    {
        return Crypt::decryptString($this->payout_phone_encrypted);
    }

    // BC alias used by old code paths
    public function setPayoutPhone(string $phone): void
    {
        $this->setPayoutDestination($phone, 'phone');
    }

    public function getPayoutPhone(): string
    {
        return $this->getPayoutDestination();
    }
}
