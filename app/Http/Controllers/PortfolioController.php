<?php

namespace App\Http\Controllers;

use App\Models\PortfolioItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortfolioController extends Controller
{
    /**
     * POST /api/v1/profile/portfolio
     */
    public function store(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'type' => 'required|in:event,certificate,award',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'brand_name' => 'nullable|string|max:255',
            'skills_demonstrated' => 'nullable|array',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov|max:20480',
            'is_public' => 'boolean',
            'order_index' => 'integer',
        ]);

        // Upload media files
        $mediaUrls = [];
        if ($request->hasFile('media')) {
            foreach ($request->file('media') as $file) {
                $mediaUrls[] = $file->store('portfolio/media', 'public');
            }
        }

        $portfolio = PortfolioItem::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'brand_name' => $request->brand_name,
            'skills_demonstrated' => $request->skills_demonstrated,
            'media_urls' => $mediaUrls,
            'is_public' => $request->is_public ?? true,
            'order_index' => $request->order_index ?? 0,
        ]);

        return response()->json([
            'message' => 'Portfolio item added successfully',
            'data' => $portfolio
        ], 201);
    }

    /**
     * PUT /api/v1/profile/portfolio/{id}
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $portfolio = PortfolioItem::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'date' => 'nullable|date',
            'brand_name' => 'nullable|string|max:255',
            'skills_demonstrated' => 'nullable|array',
            'is_public' => 'boolean',
            'order_index' => 'integer',
        ]);

        $portfolio->update($request->only([
            'title',
            'description',
            'date',
            'brand_name',
            'skills_demonstrated',
            'is_public',
            'order_index',
        ]));

        return response()->json([
            'message' => 'Portfolio item updated successfully',
            'data' => $portfolio
        ]);
    }

    /**
     * DELETE /api/v1/profile/portfolio/{id}
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $portfolio = PortfolioItem::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $portfolio->delete();

        return response()->json([
            'message' => 'Portfolio item deleted successfully'
        ]);
    }
}
