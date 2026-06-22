<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Get reviews for a product
     */
    public function index(Product $product)
    {
        $reviews = $product->reviews()->with('user')->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $reviews,
        ]);
    }

    /**
     * Create a review (authenticated users only)
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);

        // Check if user already reviewed
        $existingReview = Review::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reviewed this product',
            ], 422);
        }

        // Check if user purchased this product
        $hasPurchased = $request->user()->orders()
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();

        if (!$hasPurchased) {
            return response()->json([
                'success' => false,
                'message' => 'You can only review products you have purchased',
            ], 422);
        }

        $review = $request->user()->reviews()->create([
            'product_id' => $product->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        $review->load('user');

        return response()->json([
            'success' => true,
            'message' => 'Review posted successfully',
            'data' => $review,
        ], 201);
    }

    /**
     * Update review
     */
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);

        $review->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Review updated successfully',
            'data' => $review,
        ]);
    }

    /**
     * Delete review
     */
    public function destroy(Request $request, Review $review)
    {
        if ($review->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully',
        ]);
    }
}