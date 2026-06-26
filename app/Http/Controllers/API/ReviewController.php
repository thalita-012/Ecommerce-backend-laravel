<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ReviewController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products/{product}/reviews",
     *     operationId="listProductReviews",
     *     tags={"Reviews"},
     *     summary="Get reviews for a product",
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated product reviews",
     *         @OA\JsonContent(ref="#/components/schemas/ReviewPageResponse")
     *     ),
     *     @OA\Response(response=404, description="Product not found")
     * )
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
     * @OA\Post(
     *     path="/api/products/{product}/reviews",
     *     operationId="createProductReview",
     *     tags={"Reviews"},
     *     summary="Create a review for a purchased product",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ReviewRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Review posted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ReviewResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation or business rule error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/reviews/{review}",
     *     operationId="updateReview",
     *     tags={"Reviews"},
     *     summary="Update an existing review",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="review",
     *         in="path",
     *         required=true,
     *         description="Review ID",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/ReviewRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ReviewResponse")
     *     ),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/reviews/{review}",
     *     operationId="deleteReview",
     *     tags={"Reviews"},
     *     summary="Delete a review",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="review",
     *         in="path",
     *         required=true,
     *         description="Review ID",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Review deleted successfully",
     *         @OA\JsonContent(ref="#/components/schemas/MessageResponse")
     *     ),
     *     @OA\Response(response=403, description="Unauthorized"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
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
