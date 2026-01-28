<?php

namespace App\Http\Controllers\Promoter;

use App\Http\Controllers\Controller;
use App\Models\PromoterPost;
use App\Models\PromoterPostMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PromoterPostController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'caption' => 'nullable|string|max:1000',
            'photos.*' => 'nullable|image|max:5120', // 5MB each
            'video' => 'nullable|mimes:mp4,mov,avi|max:51200', // 50MB
        ]);

        $post = PromoterPost::create([
            'user_id' => Auth::id(),
            'caption' => $request->caption,
        ]);

        // Upload photos
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('promoter/posts/photos', 'public');

                PromoterPostMedia::create([
                    'promoter_post_id' => $post->id,
                    'type' => 'photo',
                    'path' => $path,
                ]);
            }
        }

        // Upload video
        if ($request->hasFile('video')) {
            $path = $request->file('video')->store('promoter/posts/videos', 'public');

            PromoterPostMedia::create([
                'promoter_post_id' => $post->id,
                'type' => 'video',
                'path' => $path,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Post created successfully',
            'data' => $post->load('media'),
        ]);
    }

    public function index()
    {
        $posts = PromoterPost::where('user_id', Auth::id())
            ->with('media')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $posts
        ]);
    }
}
