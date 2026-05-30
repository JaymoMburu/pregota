<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditorPayout extends Model
{
    protected $fillable = [
        'creditor_phone_hash', 'contact_id', 'recipient_name',
        'recipient_phone_encrypted', 'recipient_till',
        'amount', 'category', 'description',
        'checkout_request_id', 'status', 'receipt_number', 'b2c_response',
    ];
}
