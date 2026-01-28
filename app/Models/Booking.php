<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'promoter_id',
        'brand_id',
        'status',
        'booking_date',
        'confirmed_at',
        'contract_details',
        'terms',
        'check_in_time',
        'check_out_time',
        'payment_status',
        'payment_released_at',
        'cancellation_reason',
        'cancelled_by',
    ];

    protected $casts = [
        'contract_details'     => 'array',
        'terms'                => 'array',
        'confirmed_at'         => 'datetime',
        'payment_released_at'  => 'datetime',
        'booking_date'         => 'date',
    ];

    /* =======================
       Relationships
    ======================== */

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function promoter()
    {
        return $this->belongsTo(Profile::class, 'promoter_id');
    }

    public function brand()
    {
        return $this->belongsTo(Profile::class, 'brand_id');
    }
}
