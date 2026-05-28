<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayLinkFare extends Model
{
    protected $fillable = ['pay_link_id', 'label', 'amount', 'sort_order'];
}
