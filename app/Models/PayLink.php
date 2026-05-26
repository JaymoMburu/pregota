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
        'current_route', 'current_fare',
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

    // For transport: kca123a → KCA 123A. Others: return handle as-is.
    public function displayIdentifier(): string
    {
        if ($this->category !== 'transport') {
            return $this->handle;
        }

        $slug = strtoupper($this->handle); // KCA123A
        // Standard Kenyan plate: 3 letters + 3 digits + optional letter (6 or 7 chars)
        if (preg_match('/^([A-Z]{3})(\d{3}[A-Z]?)$/', $slug, $m)) {
            return $m[1] . ' ' . $m[2]; // KCA 123A
        }

        return $slug;
    }
}
