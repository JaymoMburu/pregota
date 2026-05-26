<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deni extends Model
{
    protected $table = 'deni';

    protected $fillable = [
        'pay_link_id', 'creditor_name', 'admin_token', 'debtor_token',
        'debtor_phone_hash', 'lender_phone_encrypted', 'description',
        'original_amount', 'amount_paid', 'status', 'due_date',
    ];

    protected $casts = ['due_date' => 'date'];

    public function payLink()   { return $this->belongsTo(PayLink::class); }
    public function payments()  { return $this->hasMany(DeniPayment::class); }

    public function balance(): int { return max(0, $this->original_amount - $this->amount_paid); }

    public function creditorLabel(): string
    {
        return $this->payLink?->business_name ?? $this->creditor_name ?? 'Unknown';
    }

    public function syncStatus(): void
    {
        if ($this->amount_paid >= $this->original_amount) {
            $this->status = 'settled';
        } elseif ($this->amount_paid > 0) {
            $this->status = 'partial';
        } else {
            $this->status = 'open';
        }
        $this->save();
    }
}
