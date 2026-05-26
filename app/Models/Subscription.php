<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'plan_id', 'phone_hash', 'reminder_token', 'status',
        'last_paid_at', 'next_due_at', 'checkout_request_id', 'receipt_number',
    ];

    protected $casts = [
        'last_paid_at' => 'date',
        'next_due_at'  => 'date',
    ];

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function isDue(): bool
    {
        return $this->next_due_at && $this->next_due_at->isPast();
    }
}
