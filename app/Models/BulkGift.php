<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BulkGift extends Model
{
    protected $fillable = [
        'reference', 'company_name', 'contact_name',
        'amount_per_code', 'code_count',
        'total_payout', 'fee_in_total', 'fee_out_total', 'gross_amount',
        'status', 'mpesa_checkout_id', 'mpesa_confirmation_code',
    ];

    protected $casts = [
        'amount_per_code' => 'decimal:2',
        'total_payout'    => 'decimal:2',
        'fee_in_total'    => 'decimal:2',
        'fee_out_total'   => 'decimal:2',
        'gross_amount'    => 'decimal:2',
    ];

    public function vouchers(): HasMany
    {
        return $this->hasMany(Voucher::class);
    }

    public static function generateReference(): string
    {
        do {
            $part1 = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
            $part2 = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
            $ref   = "BULK-{$part1}-{$part2}";
        } while (static::where('reference', $ref)->exists());

        return $ref;
    }

    public function isPending(): bool  { return $this->status === 'pending'; }
    public function isActive(): bool   { return $this->status === 'active'; }
}
