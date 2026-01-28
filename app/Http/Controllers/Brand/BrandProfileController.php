<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\Controller;
use App\Models\BrandDetail;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BrandProfileController extends Controller
{
    /**
     * GET /api/v1/brand/profile
     * 
     */

    public function show()
    {
        $user = Auth::user();

        // $profile = Profile::with('brandDetail')
        //     ->where('user_id', $user->id)
        //     ->first();
        $profile = Profile::with('brandDetail')->get();



        return response()->json([
            'status' => true,
            'data' => $profile
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'gst_number'   => 'nullable|string|max:50',
            'industry'     => 'nullable|string|max:100',
            'company_size' => 'nullable|string|max:50',
            'website'      => 'nullable|url',
            'social_profiles' => 'nullable|array',
        ]);

        $user = Auth::user();

        // 1️⃣ Ensure profile exists
        $profile = Profile::updateOrCreate(
            ['user_id' => $user->id],
            ['type' => 'brand']
        );

        // 2️⃣ Brand details
        $brand = BrandDetail::updateOrCreate(
            ['profile_id' => $profile->id],
            $request->only([
                'company_name',
                'gst_number',
                'industry',
                'company_size',
                'website',
                'social_profiles'
            ])
        );

        return response()->json([
            'status' => true,
            'message' => 'Brand profile updated successfully',
            'data' => $brand
        ]);
    }


    /**
     * POST /api/v1/brand/profile/upload-docs
     */
    public function uploadDocs(Request $request)
    {
        $request->validate([
            'gst_doc' => 'nullable|file|mimes:pdf,jpg,png|max:5120'
        ]);

        $profile = Auth::user()->profile;
        $brand = $profile->brandDetail;

        $docs = $brand->verification_docs ?? [];

        if ($request->hasFile('gst_doc')) {
            $docs['gst'] = $request->file('gst_doc')
                ->store('brand/docs', 'public');
        }

        $brand->update([
            'verification_docs' => $docs
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Documents uploaded successfully'
        ]);
    }
}
