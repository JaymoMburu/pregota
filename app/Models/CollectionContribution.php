<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionContribution extends Model
{
    protected $fillable = [
        'collection_id', 'contributor_name', 'amount', 'fee', 'gross_amount',
        'mpesa_checkout_id', 'mpesa_confirmation_code', 'status',
    ];

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }

    public function displayName(): string
    {
        return $this->contributor_name ?: 'Anonymous';
    }
}
