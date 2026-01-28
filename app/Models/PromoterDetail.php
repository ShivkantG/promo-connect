<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PromoterDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'skills',
        'price_range',
        'availability',
        'portfolio_stats',
        'badges',
        'cover_photo',
        'profile_photo',
        'intro_video'
    ];

    protected $casts = [
        'categories' => 'array',
        'skills' => 'array',
        'languages' => 'array',
        'price_range' => 'array',
        'availability' => 'array',
        'portfolio_stats' => 'array',
        'badges' => 'array',
    ];

    /**
     * PromoterDetail belongs to Profile
     */
    public function profile(): BelongsTo
    {
        return $this->belongsTo(Profile::class);
    }
}
