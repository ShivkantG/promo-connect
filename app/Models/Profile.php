<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    // protected $casts = [
    //     'basic_info' => 'array',
    //     'professional_info' => 'array',
    // ];


    protected $fillable = [
        'user_id',
        'type',
        'basic_info',
        'professional_info',
        'preferences',
        'settings',
        'rating',
        'review_count',
        'completion_rate',
        'is_active',
    ];

    protected $casts = [
        'basic_info' => 'array',
        'professional_info' => 'array',
        'preferences' => 'array',
        'settings' => 'array',
    ];

    /**
     * Profile belongs to User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Promoter-specific details
     */
    public function promoterDetail()
    {
        return $this->hasOne(PromoterDetail::class);
    }

    /**
     * Brand-specific details
     */
    // public function brandDetail()
    // {
    //     return $this->hasOne(BrandDetail::class);
    // }

    public function logs()
    {
        return $this->hasMany(ProfileLog::class);
    }

    public function brandDetail()
    {
        return $this->hasOne(BrandDetail::class);
    }

    // public function eventApplications()
    // {
    //     return $this->hasMany(EventApplication::class);
    // }
}
