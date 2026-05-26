<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContributionGroup extends Model
{
    protected $fillable = [
        'slug', 'name', 'description', 'admin_phone_encrypted', 'admin_pin_hash',
        'amount_per_member', 'frequency', 'next_due', 'is_active',
    ];

    protected $casts = ['next_due' => 'date', 'is_active' => 'boolean'];

    public function payments()
    {
        return $this->hasMany(GroupPayment::class, 'group_id');
    }

    public function currentPeriod(): string
    {
        $now = now();
        return match($this->frequency) {
            'monthly'   => $now->format('Y-m'),
            'quarterly' => $now->format('Y') . '-Q' . ceil($now->month / 3),
            'annually'  => $now->format('Y'),
            default     => 'once',
        };
    }
}
