<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    protected $fillable = [
        'school_collection_id', 'class_name', 'teacher_name',
        'class_token', 'teacher_token', 'total_raised', 'contributor_count',
    ];

    public function schoolCollection()
    {
        return $this->belongsTo(SchoolCollection::class);
    }

    public function payments()
    {
        return $this->hasMany(SchoolPayment::class);
    }

    public function paidPayments()
    {
        return $this->payments()->where('status', 'paid');
    }
}
