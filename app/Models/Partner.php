<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = [
        'name', 'slug', 'category', 'tagline',
        'logo_emoji', 'brand_color', 'cta_text',
        'url', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q)
    {
        return $q->where('is_active', true)->orderBy('sort_order');
    }

    public function clicks()
    {
        return $this->hasMany(PartnerClick::class);
    }
}
