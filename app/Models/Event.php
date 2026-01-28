<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_id',
        'title',
        'description',
        'category',
        'subcategory',
        'event_type',
        'location',
        'dates',
        'requirements',
        'budget_details',
        'perks',
        'status',
        'visibility',
        'application_deadline',
        'auto_match_criteria',
    ];

    protected $casts = [
        'location'            => 'array',
        'dates'               => 'array',
        'requirements'        => 'array',
        'budget_details'      => 'array',
        'perks'               => 'array',
        'auto_match_criteria' => 'array',
        'application_deadline' => 'date',
    ];

    /**
     * Event belongs to Brand Profile
     */
    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    /**
     * Event applications (promoters)
     */
    // public function applications()
    // {
    //     return $this->hasMany(EventApplication::class);
    // }
}
