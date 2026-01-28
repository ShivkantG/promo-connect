<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\EventParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventParticipationController extends Controller
{
    /**
     * Create participation after event completion
     * Brand side
     */
    public function store(Request $request, $bookingId)
    {

        // $brand = auth()->user()->profile;

        $user = Auth::user();
        $brand = $user->profile;

        $booking = Booking::with('event')
            ->where('id', $bookingId)
            ->firstOrFail();


        if ($booking->brand_id !== $brand->id) {
            abort(403);
        }

        $data = $request->validate([
            'role' => 'nullable|string|max:100',
            'responsibilities' => 'nullable|string',
            'hours_worked' => 'nullable|numeric|min:0',
            'performance_metrics' => 'nullable|array',
        ]);

        $participation = EventParticipation::create([
            'booking_id' => $booking->id,
            'event_id' => $booking->event_id,
            'promoter_id' => $booking->promoter_id,
            ...$data
        ]);

        return response()->json([
            'message' => 'Participation recorded',
            'data' => $participation
        ], 201);
    }
}
