<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Investor extends Model
{
    protected $fillable = [
        'name', 'email', 'password', 'equity_pct',
        'amount_invested_kes', 'investor_type', 'notes',
        'is_active', 'last_login_at', 'last_login_ip',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'equity_pct'           => 'decimal:2',
        'amount_invested_kes'  => 'decimal:2',
        'is_active'            => 'boolean',
        'last_login_at'        => 'datetime',
    ];

    public const TYPES = [
        'angel'     => 'Angel Investor',
        'vc'        => 'Venture Capital',
        'strategic' => 'Strategic Partner',
        'grant'     => 'Grant / Non-dilutive',
    ];

    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function verifyPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->investor_type] ?? ucfirst($this->investor_type);
    }
}
