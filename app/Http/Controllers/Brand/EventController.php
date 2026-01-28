<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{

    /**
     * GET /api/v1/brand/events
     * 
     */
    public function index()
    {
        $profile = Auth::user()->profile;

        // Only brand allowed   
        // if ($profile->type !== 'brand') {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Only brand accounts can access events'
        //     ], 403);
        // }

        // $events = Event::where('profile_id', $profile->id)
        //     ->orderBy('created_at', 'desc')
        //     ->get();
        $events = Event::with('profile')->get();
        return response()->json([
            'status' => true,
            'data' => $events
        ]);
    }

    /**
     * POST /api/v1/brand/events
     * Brand create event (organize event)
     */
    public function store(Request $request)
    {
        $profile = Auth::user()->profile;

        // Only brand allowed
        if ($profile->type !== 'brand') {
            return response()->json([
                'status' => false,
                'message' => 'Only brand accounts can create events'
            ], 403);
        }

        // Validation
        $validated = $request->validate([
            'title'              => 'required|string|max:255',
            'description'        => 'nullable|string',
            'category'           => 'required|string|max:100',
            'subcategory'        => 'nullable|string|max:100',
            'event_type'         => 'required|in:online,offline,hybrid',

            'location'           => 'nullable|array',
            'dates'              => 'nullable|array',
            'requirements'       => 'nullable|array',
            'budget_details'     => 'nullable|array',
            'perks'              => 'nullable|array',
            'visibility'         => 'required|in:public,private',
            'application_deadline' => 'nullable|date',
            'auto_match_criteria' => 'nullable|array',
        ]);

        // 🔹 Plan limit logic (basic vs pro)
        if ($profile->plan === 'basic') {
            $monthlyCount = Event::where('profile_id', $profile->id)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count();

            if ($monthlyCount >= 1) {
                return response()->json([
                    'status' => false,
                    'message' => 'Basic plan allows only 1 event per month'
                ], 403);
            }
        }

        // Create event
        $event = Event::create([
            'profile_id' => $profile->id,
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'],
            'subcategory' => $validated['subcategory'] ?? null,
            'event_type' => $validated['event_type'],

            'location' => $validated['location'] ?? null,
            'dates' => $validated['dates'] ?? null,
            'requirements' => $validated['requirements'] ?? null,
            'budget_details' => $validated['budget_details'] ?? null,
            'perks' => $validated['perks'] ?? null,

            'visibility' => $validated['visibility'],
            'application_deadline' => $validated['application_deadline'] ?? null,
            'auto_match_criteria' => $validated['auto_match_criteria'] ?? null,

            'status' => 'open'
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Event created successfully',
            'data' => $event
        ], 201);
    }

    /**
     * GET /api/v1/brand/events/{id}
     * Single event detail
     */
    public function show($id)
    {
        $profile = Auth::user()->profile;

        $event = Event::where('id', $id)
            ->where('profile_id', $profile->id)
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'data' => $event
        ]);
    }

    /**
     * PUT /api/v1/brand/events/{id}
     * Event update
     */
    public function update(Request $request, $id)
    {
        $profile = Auth::user()->profile;

        $event = Event::where('id', $id)
            ->where('profile_id', $profile->id)
            ->firstOrFail();

        $validated = $request->validate([
            'title'              => 'nullable|string|max:255',
            'description'        => 'nullable|string',
            'category'           => 'nullable|string|max:100',
            'subcategory'        => 'nullable|string|max:100',
            'event_type'         => 'nullable|in:online,offline,hybrid',

            'location'           => 'nullable|array',
            'dates'              => 'nullable|array',
            'requirements'       => 'nullable|array',
            'budget_details'     => 'nullable|array',
            'perks'              => 'nullable|array',
            'visibility'         => 'nullable|in:public,private',
            'application_deadline' => 'nullable|date',
            'auto_match_criteria' => 'nullable|array',
            'status'             => 'nullable|in:draft,open,closed,completed'
        ]);

        $event->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Event updated successfully',
            'data' => $event
        ]);
    }

    /**
     * DELETE /api/v1/brand/events/{id}
     * Event delete
     */
    public function destroy($id)
    {
        $profile = Auth::user()->profile;

        $event = Event::where('id', $id)
            ->where('profile_id', $profile->id)
            ->firstOrFail();

        $event->delete();

        return response()->json([
            'status' => true,
            'message' => 'Event deleted successfully'
        ]);
    }
}
