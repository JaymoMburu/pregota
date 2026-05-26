<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyerStamp extends Model
{
    protected $fillable = [
        'pay_link_id', 'phone_hash', 'stamp_count', 'reward_pending', 'last_stamp_at',
    ];

    protected $casts = [
        'reward_pending' => 'boolean',
        'last_stamp_at'  => 'datetime',
    ];

    public function payLink()
    {
        return $this->belongsTo(PayLink::class);
    }
}
