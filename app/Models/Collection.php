<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Collection extends Model
{
    protected $fillable = [
        'slug', 'title', 'description', 'photo_path', 'occasion', 'organiser_name', 'organiser_phone', 'recipient_name',
        'recipient_phone_encrypted', 'target_amount', 'per_person_amount', 'preset_amounts', 'deadline',
        'payout_trigger', 'status', 'manage_token',
        'total_raised', 'contributor_count',
        'b2c_conversation_id', 'paid_out_at',
        'phone_verified', 'is_frozen', 'freeze_reason', 'verification_checkout_id',
    ];

    protected $casts = [
        'deadline'       => 'datetime',
        'paid_out_at'    => 'datetime',
        'preset_amounts' => 'array',
    ];

    public function contributions()
    {
        return $this->hasMany(CollectionContribution::class);
    }

    public function paidContributions()
    {
        return $this->contributions()->where('status', 'paid');
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
        if (! $this->target_amount || $this->target_amount === 0) return 0;
        return min(100, (int) round($this->total_raised / $this->target_amount * 100));
    }

    public function isDeadlinePassed(): bool
    {
        return $this->deadline && $this->deadline->lt(now());
    }

    public function occasionEmoji(): string
    {
        return match($this->occasion) {
            'bereavement' => '🕊️',
            'wedding'     => '💍',
            'medical'     => '🏥',
            'farewell'    => '👋',
            'education'   => '🎓',
            default       => '🤝',
        };
    }

    public function occasionLabel(): string
    {
        return match($this->occasion) {
            'bereavement' => 'Bereavement Welfare',
            'wedding'     => 'Wedding Contribution',
            'medical'     => 'Medical Support',
            'farewell'    => 'Farewell Collection',
            'education'   => 'Education Support',
            default       => 'Group Collection',
        };
    }
}
