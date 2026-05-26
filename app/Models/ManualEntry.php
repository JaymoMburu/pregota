<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManualEntry extends Model
{
    protected $fillable = [
        'phone_hash', 'type', 'amount', 'category', 'description', 'entry_date',
    ];

    protected $casts = [
        'entry_date' => 'date',
    ];
}
