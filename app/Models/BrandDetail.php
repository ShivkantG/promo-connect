<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BrandDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'company_name',
        'gst_number',
        'industry',
        'company_size',
        'website',
        'social_profiles',
        'verification_docs',
        'brand_score',
        'promoter_satisfaction_rate',
    ];

    protected $casts = [
        'social_profiles' => 'array',
        'verification_docs' => 'array',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }
}
