<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class SchoolCollection extends Model
{
    protected $fillable = [
        'slug', 'school_name', 'term_label', 'amount_per_student',
        'admin_name', 'recipient_phone_encrypted', 'admin_token',
        'status', 'total_raised', 'contributor_count',
        'b2c_conversation_id', 'paid_out_at',
        'phone_verified', 'is_frozen', 'freeze_reason', 'verification_checkout_id',
    ];

    protected $casts = ['paid_out_at' => 'datetime'];

    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function setRecipientPhone(string $phone): void
    {
        $this->recipient_phone_encrypted = Crypt::encryptString($phone);
    }

    public function getRecipientPhone(): string
    {
        return Crypt::decryptString($this->recipient_phone_encrypted);
    }

    public function isOpen(): bool { return $this->status === 'open'; }
    public function isPaid(): bool { return $this->status === 'paid'; }
    public function isAccepting(): bool { return $this->isOpen() && $this->phone_verified && ! $this->is_frozen; }

    public function progressPct(): int
    {
        $target = $this->classes()->sum('contributor_count') > 0
            ? $this->classes()->count() * 30
            : 0;
        if (! $target) return 0;
        return min(100, (int) round($this->contributor_count / $target * 100));
    }
}
