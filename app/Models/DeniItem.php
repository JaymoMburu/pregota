<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeniItem extends Model
{
    protected $fillable = ['deni_id', 'description', 'amount'];

    public function deni() { return $this->belongsTo(Deni::class, 'deni_id', 'id', 'deni'); }
}
