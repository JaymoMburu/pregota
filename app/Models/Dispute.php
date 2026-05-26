<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dispute extends Model
{
    protected $fillable = [
        'receipt_number', 'buyer_phone_encrypted',
        'issue_type', 'description', 'status', 'admin_note',
    ];

    public function payment()
    {
        return $this->belongsTo(SellerPayment::class, 'receipt_number', 'receipt_number');
    }
}
