<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolPayment extends Model
{
    protected $fillable = [
        'school_class_id', 'student_id', 'student_name', 'amount', 'fee', 'gross_amount',
        'mpesa_checkout_id', 'mpesa_confirmation_code', 'status', 'paid_at',
    ];

    protected $casts = ['paid_at' => 'datetime'];

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class);
    }
}
