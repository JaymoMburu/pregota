<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class DirectGift extends Model
{
    protected $fillable = [
        'gross_amount', 'gift_amount', 'fee',
        'mpesa_checkout_id', 'mpesa_confirmation_code',
        'status', 'recipient_phone_encrypted',
        'b2c_conversation_id', 'paid_at',
    ];

    protected $casts = ['paid_at' => 'datetime'];

    public function setRecipientPhone(string $phone): void
    {
        $this->recipient_phone_encrypted = Crypt::encryptString($phone);
    }

    public function getRecipientPhone(): string
    {
        return Crypt::decryptString($this->recipient_phone_encrypted);
    }
}
