<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FraudReport extends Model
{
    protected $fillable = ['reportable_type', 'reportable_id', 'reason'];

    public function reportable()
    {
        return $this->morphTo();
    }
}
