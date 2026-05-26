<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyerPin extends Model
{
    protected $primaryKey = 'phone_hash';
    public    $incrementing = false;
    protected $keyType      = 'string';

    protected $fillable = ['phone_hash', 'pin_hash'];
    protected $hidden   = ['pin_hash'];
}
