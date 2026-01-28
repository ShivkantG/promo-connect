<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PortfolioItem extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'title',
        'description',
        'date',
        'brand_name',
        'media_urls',
        'skills_demonstrated',
        'is_public',
        'order_index',
    ];

    protected $casts = [
        'media_urls' => 'array',
        'skills_demonstrated' => 'array',
        'verified' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
