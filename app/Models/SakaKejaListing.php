<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SakaKejaListing extends Model
{
    protected $fillable = [
        'landlord_phone_hash', 'landlord_phone_encrypted', 'landlord_name',
        'location', 'unit_type', 'rent', 'deposit_amount', 'advance_months', 'utility_fees',
        'description', 'photos', 'verification_checkout_id', 'listing_fee', 'status',
    ];

    protected $casts = [
        'photos'        => 'array',
        'utility_fees'  => 'array',
    ];

    public function connections()
    {
        return $this->hasMany(SakaKejaConnection::class, 'listing_id');
    }

    public function deposits()
    {
        return $this->hasMany(SakaKejaDeposit::class, 'listing_id');
    }

    public function rentPayments()
    {
        return $this->hasMany(SakaKejaRentPayment::class, 'listing_id');
    }

    public function totalSecureAmount(): int
    {
        $deposit   = $this->deposit_amount ?? $this->rent;
        $advance   = $this->rent * ($this->advance_months ?? 1);
        $utilities = collect($this->utility_fees ?? [])->sum('amount');
        return $deposit + $advance + (int) $utilities;
    }

    public function unitLabel(): string
    {
        return match($this->unit_type) {
            'bedsitter' => 'Bedsitter',
            '1br'       => '1 Bedroom',
            '2br'       => '2 Bedrooms',
            '3br'       => '3 Bedrooms',
            'studio'    => 'Studio',
            'shop'      => 'Shop / Commercial',
            default     => ucfirst($this->unit_type),
        };
    }

    public function firstPhoto(): ?string
    {
        $photos = $this->photos ?? [];
        return count($photos) ? $photos[0] : null;
    }
}
