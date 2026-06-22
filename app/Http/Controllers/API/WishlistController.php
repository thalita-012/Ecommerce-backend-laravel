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
        $wishlists = $request->user()->wishlists()->with('product')->get();

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
        $wishlist->load('product');

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
        if ($wishlist->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

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