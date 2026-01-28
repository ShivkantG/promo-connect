<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * Brand creates booking for promoter
     * POST /api/v1/bookings
     */
    public function store(Request $request)
    {
        $profile = Auth::user()->profile; // brand profile

        $request->validate([
            'event_id'    => 'required|exists:events,id',
            'promoter_id' => 'required|exists:profiles,id',
            'booking_date' => 'required|date',
        ]);

        $event = Event::findOrFail($request->event_id);

        $booking = Booking::create([
            'event_id'     => $event->id,
            'brand_id'     => $profile->id,
            'promoter_id'  => $request->promoter_id,
            'booking_date' => $request->booking_date,
            'status'       => 'pending',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Booking request sent',
            'data'    => $booking
        ], 201);
    }

    /**
     * Promoter accepts / rejects booking
     * PUT /api/v1/bookings/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $profile = Auth::user()->profile;

        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);

        $booking = Booking::where('id', $id)
            ->where('promoter_id', $profile->id)
            ->firstOrFail();

        $booking->update([
            'status'       => $request->status,
            'confirmed_at' => $request->status === 'accepted' ? now() : null,
        ]);

        return response()->json([
            'message' => 'Booking status updated',
            'data'    => $booking
        ]);
    }

    /**
     * Cancel booking
     */
    public function cancel(Request $request, $id)
    {
        $profile = Auth::user()->profile;

        $request->validate([
            'reason' => 'required|string'
        ]);

        $booking = Booking::where('id', $id)
            ->where(function ($q) use ($profile) {
                $q->where('brand_id', $profile->id)
                    ->orWhere('promoter_id', $profile->id);
            })
            ->firstOrFail();

        $booking->update([
            'status'              => 'cancelled',
            'cancellation_reason' => $request->reason,
            'cancelled_by'        => $profile->type,
        ]);

        return response()->json([
            'message' => 'Booking cancelled'
        ]);
    }
}
