<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipTransaction extends Model
{
    protected $fillable = [
        'staff_member_id', 'gross_amount', 'tip_amount', 'fee_in', 'fee_out',
        'status', 'mpesa_checkout_id', 'mpesa_confirmation_code',
        'b2c_conversation_id', 'activated_at', 'paid_at', 'feedback_submitted',
    ];

    protected $casts = [
        'activated_at'       => 'datetime',
        'paid_at'            => 'datetime',
        'feedback_submitted' => 'boolean',
    ];

    public function staff()
    {
        return $this->belongsTo(StaffMember::class, 'staff_member_id');
    }

    public function feedback()
    {
        return $this->hasOne(TipFeedback::class);
    }
}
