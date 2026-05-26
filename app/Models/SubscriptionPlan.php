<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = ['pay_link_id', 'name', 'description', 'amount', 'frequency', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function payLink()
    {
        return $this->belongsTo(PayLink::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function nextDueDate(): \Carbon\Carbon
    {
        return match($this->frequency) {
            'monthly'   => now()->addMonth(),
            'quarterly' => now()->addMonths(3),
            'annually'  => now()->addYear(),
        };
    }

    public function frequencyLabel(): string
    {
        return match($this->frequency) {
            'monthly'   => 'month',
            'quarterly' => 'quarter',
            'annually'  => 'year',
        };
    }
}
