<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditorContact extends Model
{
    protected $fillable = ['creditor_phone_hash', 'name', 'phone_encrypted', 'till'];
}
