<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerClick extends Model
{
    protected $fillable = ['partner_id', 'voucher_code', 'ip'];

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }
}
