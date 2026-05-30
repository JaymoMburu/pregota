<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SakaKejaRentPayment extends Model
{
    protected $fillable = [
        'deposit_id', 'listing_id', 'rent_month',
        'gross_amount', 'fee_amount', 'net_amount',
        'checkout_request_id', 'status', 'receipt_number',
    ];

    public function deposit()
    {
        return $this->belongsTo(SakaKejaDeposit::class, 'deposit_id');
    }

    public function listing()
    {
        return $this->belongsTo(SakaKejaListing::class, 'listing_id');
    }
}
