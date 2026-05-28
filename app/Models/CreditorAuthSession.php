<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditorAuthSession extends Model
{
    protected $fillable = ['checkout_request_id', 'phone_hash', 'phone_encrypted', 'display_name', 'status'];
}
