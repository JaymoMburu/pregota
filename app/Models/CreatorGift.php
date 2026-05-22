<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreatorGift extends Model
{
    protected $fillable = [
        'creator_id', 'gross_amount', 'payout_amount',
        'fee_in', 'fee_out', 'fan_name', 'message',
        'mpesa_checkout_id', 'mpesa_confirmation_code',
        'b2c_conversation_id', 'status', 'alert_shown',
    ];

    protected $casts = ['alert_shown' => 'boolean'];

    public function creator()
    {
        return $this->belongsTo(Creator::class);
    }
}
