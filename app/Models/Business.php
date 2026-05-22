<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Business extends Authenticatable
{
    protected $fillable = [
        'name', 'slug', 'category', 'logo_emoji', 'description',
        'email', 'password', 'city', 'plan', 'plan_expires_at',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'password'        => 'hashed',
        'plan_expires_at' => 'datetime',
    ];

    public function staff()
    {
        return $this->hasMany(StaffMember::class);
    }

    public function activeStaff()
    {
        return $this->staff()->where('active', true);
    }

    public function isSubscribed(): bool
    {
        return $this->plan !== 'free'
            && ($this->plan_expires_at === null || $this->plan_expires_at->gt(now()));
    }

    public function planLabel(): string
    {
        return match($this->plan) {
            'starter'    => 'Starter',
            'growth'     => 'Growth',
            'business'   => 'Business',
            'enterprise' => 'Enterprise',
            default      => 'Free',
        };
    }

    public function categoryLabel(): string
    {
        return match($this->category) {
            'restaurant' => 'Restaurant',
            'salon'      => 'Salon & Spa',
            'hotel'      => 'Hotel',
            'delivery'   => 'Delivery',
            default      => 'Business',
        };
    }
}
