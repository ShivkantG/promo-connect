<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use App\Models\PromoterDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PromoterController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $promoter = PromoterDetail::where('user_id', $user->id)->first();

        return response()->json([
            'status' => true,
            'data' => $promoter
        ]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        // Validation
        $validator = Validator::make($request->all(), [
            'full_name'        => 'required|string|max:255',
            'mobile_number'    => 'nullable|string|max:20',
            'instagram_link'   => 'nullable|url',
            'youtube_link'     => 'nullable|url',
            'facebook_link'    => 'nullable|url',
            'city'             => 'nullable|string|max:100',
            'state'            => 'nullable|string|max:100',
            'address'          => 'nullable|string',
            'category'         => 'nullable|string|max:100',
            'audience_size'    => 'nullable|integer',
            'experience_years' => 'nullable|integer',
            'languages'        => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Create or Update record
        $promoter = PromoterDetail::updateOrCreate(
            ['user_id' => $user->id],
            $request->except(['profile_photo', 'intro_video'])
        );

        // Profile completion logic
        $promoter->profile_completed = $this->isProfileCompleted($promoter);
        $promoter->save();

        return response()->json([
            'status' => true,
            'message' => 'Promoter profile saved successfully',
            'data' => $promoter
        ]);
    }

    //Check profile completion
    private function isProfileCompleted(PromoterDetail $promoter): bool
    {
        return !empty($promoter->full_name)
            && !empty($promoter->mobile_number)
            && !empty($promoter->profile_photo)
            && !empty($promoter->category)
            && !empty($promoter->instagram_link);
    }


    public function uploadDocument(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'profile_photo' => 'nullable|image|max:5120',          // 5MB
            'intro_video'   => 'nullable|mimes:mp4,mov,avi|max:20000' // ~20MB
        ]);

        $data = [];

        // Profile photo upload
        if ($request->hasFile('profile_photo')) {
            $data['profile_photo'] = $request->file('profile_photo')
                ->store('profiles/photos', 'public');
        }

        // Intro video upload
        if ($request->hasFile('intro_video')) {
            $data['intro_video'] = $request->file('intro_video')
                ->store('profiles/videos', 'public');
        }


        PromoterDetail::updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

        return response()->json([
            'message' => 'Documents uploaded successfully',
            'files'   => $data
        ]);
    }
}
