<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class WishlistController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/wishlist",
     *     operationId="listWishlist",
     *     tags={"Wishlist"},
     *     summary="Get the authenticated user's wishlist",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Wishlist list",
     *         @OA\JsonContent(ref="#/components/schemas/WishlistListResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
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
     * @OA\Post(
     *     path="/api/wishlist",
     *     operationId="addWishlist",
     *     tags={"Wishlist"},
     *     summary="Add a product to the wishlist",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer", example=10)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Added to wishlist",
     *         @OA\JsonContent(ref="#/components/schemas/WishlistItemResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation or duplicate item error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/wishlist/{wishlist}",
     *     operationId="removeWishlist",
     *     tags={"Wishlist"},
     *     summary="Remove an item from the wishlist",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="wishlist",
     *         in="path",
     *         required=true,
     *         description="Wishlist item ID",
     *         @OA\Schema(type="integer", example=201)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Removed from wishlist",
     *         @OA\JsonContent(ref="#/components/schemas/MessageResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
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
     * @OA\Get(
     *     path="/api/wishlist/check/{productId}",
     *     operationId="checkWishlist",
     *     tags={"Wishlist"},
     *     summary="Check if a product is in the wishlist",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wishlist status",
     *         @OA\JsonContent(ref="#/components/schemas/CheckWishlistResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
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
