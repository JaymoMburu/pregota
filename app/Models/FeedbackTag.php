<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackTag extends Model
{
    protected $fillable = ['tag', 'emoji', 'category', 'active', 'sort_order'];
    protected $casts    = ['active' => 'boolean'];
}
