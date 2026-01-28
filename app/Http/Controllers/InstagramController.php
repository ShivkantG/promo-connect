<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class InstagramController extends Controller
{
    /**
     * Step 1: Redirect to Instagram
     */
    public function redirect()
    {
        $query = http_build_query([
            'client_id' => config('services.instagram.client_id'),
            'redirect_uri' => config('services.instagram.redirect_uri'),
            'scope' => 'instagram_basic,pages_show_list',
            'response_type' => 'code',
        ]);

        return redirect(
            'https://www.facebook.com/v18.0/dialog/oauth?' . $query
        );
    }

    /**
     * Step 2: Callback after permission
     */
    public function callback(Request $request)
    {
        $code = $request->code;

        // Exchange code → access token
        $tokenResponse = Http::post(
            'https://graph.facebook.com/v18.0/oauth/access_token',
            [
                'client_id' => config('services.instagram.client_id'),
                'client_secret' => config('services.instagram.client_secret'),
                'redirect_uri' => config('services.instagram.redirect_uri'),
                'code' => $code,
            ]
        );

        $accessToken = $tokenResponse['access_token'];

        // Fetch Instagram business account
        $pages = Http::get(
            'https://graph.facebook.com/v18.0/me/accounts',
            ['access_token' => $accessToken]
        );

        $pageId = $pages['data'][0]['id'];

        $igAccount = Http::get(
            "https://graph.facebook.com/v18.0/{$pageId}",
            [
                'fields' => 'instagram_business_account',
                'access_token' => $accessToken
            ]
        );

        $igId = $igAccount['instagram_business_account']['id'];

        // Fetch Instagram stats
        $igData = Http::get(
            "https://graph.facebook.com/v18.0/{$igId}",
            [
                'fields' => 'username,followers_count,media_count',
                'access_token' => $accessToken
            ]
        );

        // Save to profile
        // $profile = auth()->user()->profile;
        $profile = Auth::user()->profile;
        $profile->update([
            'social_accounts' => [
                'instagram' => [
                    'id' => $igId,
                    'username' => $igData['username'],
                    'followers' => $igData['followers_count'],
                    'media_count' => $igData['media_count'],
                    'access_token' => $accessToken,
                ]
            ],
            'instagram_verified' => true,
            'instagram_connected_at' => now(),
        ]);

        return response()->json([
            'message' => 'Instagram connected successfully',
            'instagram' => $igData
        ]);
    }
}
