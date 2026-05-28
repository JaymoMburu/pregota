<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessLedgerEntry extends Model
{
    protected $fillable = [
        'creditor_phone_hash', 'type', 'category', 'amount',
        'description', 'source', 'deni_payment_id', 'entry_date',
    ];

    protected $casts = ['entry_date' => 'date'];

    public function deniPayment()
    {
        return $this->belongsTo(DeniPayment::class);
    }
}
