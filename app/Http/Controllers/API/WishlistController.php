<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Get user's wishlist
     */
    public function index(Request $request)
    {
        $wishlists = $request->user()
            ->wishlists()
            ->select(['id', 'user_id', 'product_id', 'created_at'])
            ->with([
                'product' => function ($query) {
                    $query->select(['id', 'name', 'slug', 'price', 'image']);
                },
            ])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $wishlists,
        ]);
    }

    /**
     * Add product to wishlist
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        // Check if already in wishlist
        $exists = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $validated['product_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Product already in wishlist',
            ], 422);
        }

        $wishlist = $request->user()->wishlists()->create($validated);
        $wishlist->load([
            'product' => function ($query) {
                $query->select(['id', 'name', 'slug', 'price', 'image']);
            },
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Added to wishlist',
            'data' => $wishlist,
        ], 201);
    }

    /**
     * Remove from wishlist
     */
    public function destroy(Wishlist $wishlist, Request $request)
    {
        $wishlist = $request->user()
            ->wishlists()
            ->whereKey($wishlist->id)
            ->firstOrFail();

        $wishlist->delete();

        return response()->json([
            'success' => true,
            'message' => 'Removed from wishlist',
        ]);
    }

    /**
     * Check if product is in wishlist
     */
    public function check(Request $request, $productId)
    {
        $inWishlist = $request->user()->wishlists()
            ->where('product_id', $productId)
            ->exists();

        return response()->json([
            'success' => true,
            'in_wishlist' => $inWishlist,
        ]);
    }
}
