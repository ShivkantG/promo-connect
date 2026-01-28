<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventParticipation extends Model
{
    protected $fillable = [
        'booking_id',
        'event_id',
        'promoter_id',
        'role',
        'responsibilities',
        'hours_worked',
        'converted_to_portfolio',
        'performance_metrics',
    ];

    protected $casts = [
        'performance_metrics' => 'array',
        'converted_to_portfolio' => 'boolean',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function promoter()
    {
        return $this->belongsTo(Profile::class, 'promoter_id');
    }
}
