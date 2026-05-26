<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PayLink extends Model
{
    protected $fillable = [
        'handle', 'business_name', 'category', 'description',
        'phone_encrypted', 'password',
        'default_amount', 'fixed_amount', 'is_active',
        'total_received', 'payment_count',
    ];

    protected $casts = [
        'fixed_amount' => 'boolean',
        'is_active'    => 'boolean',
    ];

    protected $hidden = ['password', 'phone_encrypted'];

    public function setPhone(string $phone): void
    {
        $this->update(['phone_encrypted' => Crypt::encryptString($phone)]);
    }

    public function getPhone(): string
    {
        return Crypt::decryptString($this->phone_encrypted);
    }

    public function payments()
    {
        return $this->hasMany(SellerPayment::class);
    }
}
