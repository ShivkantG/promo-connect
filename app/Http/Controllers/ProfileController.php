<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\ProfileLog;
use App\Models\PromoterDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * GET /api/v1/profile
     */
    public function show()
    {
        $user = Auth::user();

        $profile = Profile::with('promoterDetail')
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'status' => true,
            'data' => $profile
        ]);
    }

    /**
     * PUT /api/v1/profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name'      => 'nullable|string|max:255',
            'mobile_number'  => 'nullable|string|max:20',
            'instagram_link' => 'nullable|url',
            'city'           => 'nullable|string|max:100',
            'category'       => 'nullable|string|max:100',
            'languages'      => 'nullable|string',
        ]);

        // 🔹 Get old profile (for log)
        $profile = Profile::where('user_id', $user->id)->first();
        $oldData = $profile ? $profile->toArray() : null;

        // 1️⃣ profile create/update
        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'type' => 'promoter',
                'basic_info' => [
                    'full_name' => $validated['full_name'] ?? null,
                    'mobile'    => $validated['mobile_number'] ?? null,
                    'city'      => $validated['city'] ?? null,
                ],
                'professional_info' => [
                    'category'  => $validated['category'] ?? null,
                    'languages' => $validated['languages'] ?? null,
                ],
            ]
        );
        // 2️⃣ promoter details
        PromoterDetail::updateOrCreate(
            ['profile_id' => $profile->id],
            [
                'categories' => [$validated['category'] ?? null],
                'languages'  => [$validated['languages'] ?? null],
            ]
        );
        // 3️⃣ Profile log (IMPORTANT)
        ProfileLog::create([
            'profile_id' => $profile->id,
            'user_id' => $user->id,
            'action' => $oldData ? 'update' : 'create',
            'old_data' => $oldData,
            'new_data' => $profile->fresh()->toArray(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully'
        ]);
    }
    /**
     * POST /api/v1/profile/upload-document
     */
    public function uploadDocument(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'profile_photo' => 'nullable|image|max:5120',
            'intro_video'   => 'nullable|mimes:mp4,mov,avi|max:20000',
        ]);

        $profile = Profile::where('user_id', $user->id)->firstOrFail();

        $basic = $profile->basic_info ?? [];

        if ($request->hasFile('profile_photo')) {
            $basic['profile_photo'] = $request->file('profile_photo')
                ->store('profiles/photos', 'public');
        }

        if ($request->hasFile('intro_video')) {
            $basic['intro_video'] = $request->file('intro_video')
                ->store('profiles/videos', 'public');
        }

        $profile->update([
            'basic_info' => $basic
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Documents uploaded'
        ]);
    }

    /**
     * GET /api/v1/profile/stats
     */
    // public function stats()
    // {
    //     $user = Auth::user();

    //     return response()->json([
    //         'status' => true,
    //         'data' => [
    //             'total_applications' =>
    //             EventApplication::where('promoter_id', $user->id)->count(),
    //             'portfolio_items' =>
    //             PromoterPortfolio::where('user_id', $user->id)->count(),
    //         ]
    //     ]);
    // }

    /**
     * GET /api/v1/profile/{id}/public
     */
    public function publicProfile($id)
    {
        $profile = Profile::with('promoterDetail')
            ->where('user_id', $id)
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'data' => $profile
        ]);
    }
}
