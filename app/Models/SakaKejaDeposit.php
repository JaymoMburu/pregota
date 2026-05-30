<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SakaKejaDeposit extends Model
{
    protected $fillable = [
        'listing_id', 'token', 'seeker_name', 'seeker_phone_hash', 'seeker_phone_encrypted',
        'deposit_amount', 'escrow_fee', 'total_paid', 'checkout_request_id',
        'receipt_number', 'status', 'confirmed_at', 'refunded_at',
    ];

    protected $casts = [
        'confirmed_at'          => 'datetime',
        'refunded_at'           => 'datetime',
        'move_out_requested_at' => 'datetime',
    ];

    public function listing()
    {
        return $this->belongsTo(SakaKejaListing::class, 'listing_id');
    }

    public static function generateToken(): string
    {
        do {
            $token = Str::random(32);
        } while (static::where('token', $token)->exists());

        return $token;
    }
}
