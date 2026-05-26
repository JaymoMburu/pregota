<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GroupPayment extends Model
{
    protected $fillable = [
        'group_id', 'phone_hash', 'reminder_token', 'amount',
        'period', 'checkout_request_id', 'status', 'receipt_number',
    ];

    public function group()
    {
        return $this->belongsTo(ContributionGroup::class, 'group_id');
    }
}
