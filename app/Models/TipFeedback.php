<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipFeedback extends Model
{
    protected $fillable = [
        'tip_transaction_id', 'staff_member_id', 'rating', 'tags', 'comment',
    ];

    protected $casts = ['tags' => 'array'];

    public function transaction()
    {
        return $this->belongsTo(TipTransaction::class, 'tip_transaction_id');
    }

    public function staff()
    {
        return $this->belongsTo(StaffMember::class, 'staff_member_id');
    }
}
