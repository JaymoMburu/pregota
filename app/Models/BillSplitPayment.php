<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BillSplitPayment extends Model
{
    protected $fillable = [
        'bill_split_id', 'amount', 'fee', 'gross_amount',
        'mpesa_checkout_id', 'mpesa_confirmation_code',
        'tx_hash', 'status',
    ];

    public function billSplit()
    {
        return $this->belongsTo(BillSplit::class);
    }
}
