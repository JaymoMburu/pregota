<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeniPayment extends Model
{
    protected $fillable = ['deni_id', 'amount', 'face_value', 'fee', 'checkout_request_id', 'receipt_number', 'status'];

    public function deni() { return $this->belongsTo(Deni::class); }
}
