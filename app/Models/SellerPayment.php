<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SellerPayment extends Model
{
    protected $fillable = [
        'pay_link_id', 'mpesa_checkout_id', 'mpesa_ref',
        'amount', 'fee', 'net_amount',
        'tip_amount', 'tip_recipient', 'tip_comment',
        'buyer_note', 'status', 'receipt_number',
    ];

    public function payLink()
    {
        return $this->belongsTo(PayLink::class);
    }
}
