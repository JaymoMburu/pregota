<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditorPreset extends Model
{
    protected $fillable = ['creditor_phone_hash', 'label', 'amount', 'sort_order'];
}
