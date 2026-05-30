<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SakaKejaConnection extends Model
{
    protected $fillable = [
        'listing_id', 'seeker_name', 'seeker_phone_hash', 'seeker_phone_encrypted',
        'checkout_request_id', 'status', 'receipt_number', 'amount',
    ];

    public function listing()
    {
        return $this->belongsTo(SakaKejaListing::class, 'listing_id');
    }
}
