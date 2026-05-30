<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerAuthSession extends Model
{
    protected $fillable = [
        'checkout_request_id', 'type', 'phone_hash', 'phone_encrypted',
        'pay_link_id', 'pending_data', 'status',
    ];

    protected $casts = [
        'pending_data' => 'array',
    ];
}
