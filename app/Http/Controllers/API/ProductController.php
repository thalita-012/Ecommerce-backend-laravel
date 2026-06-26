<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ProductController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/products",
     *     operationId="listProducts",
     *     tags={"Products"},
     *     summary="Get paginated products",
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         required=false,
     *         description="Filter by category slug",
     *         @OA\Schema(type="string", example="vegetables")
     *     ),
     *     @OA\Parameter(
     *         name="min_price",
     *         in="query",
     *         required=false,
     *         description="Minimum price",
     *         @OA\Schema(type="number", format="float", example=1)
     *     ),
     *     @OA\Parameter(
     *         name="max_price",
     *         in="query",
     *         required=false,
     *         description="Maximum price",
     *         @OA\Schema(type="number", format="float", example=100)
     *     ),
     *     @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         required=false,
     *         description="Sort order",
     *         @OA\Schema(type="string", enum={"latest","price-asc","price-desc"}, example="latest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Paginated products",
     *         @OA\JsonContent(ref="#/components/schemas/ProductPageResponse")
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Product::query()
            ->with('category')
            ->withCount('reviews');

        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) {
                $q->where('slug', request('category'));
            });
        }

        // Price range filter
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'latest');
        if ($sort === 'price-asc') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price-desc') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/products/{product}",
     *     operationId="showProduct",
     *     tags={"Products"},
     *     summary="Get full product details",
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         description="Product ID",
     *         @OA\Schema(type="integer", example=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product details",
     *         @OA\JsonContent(ref="#/components/schemas/ProductDetailResponse")
     *     ),
     *     @OA\Response(response=404, description="Product not found")
     * )
     */
    public function show(Product $product)
    {
        $product->load('category');
        $product->loadCount('reviews');
        $product->loadAvg('reviews', 'rating');
        $product->setAttribute(
            'rating',
            $product->reviews_avg_rating !== null ? round((float) $product->reviews_avg_rating, 1) : null
        );

        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/products/search",
     *     operationId="searchProducts",
     *     tags={"Products"},
     *     summary="Search products by keyword",
     *     @OA\Parameter(
     *         name="q",
     *         in="query",
     *         required=false,
     *         description="Search keyword",
     *         @OA\Schema(type="string", example="chili")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Search results",
     *         @OA\JsonContent(ref="#/components/schemas/ProductPageResponse")
     *     )
     * )
     */
    public function search(Request $request)
    {
        $keyword = $request->get('q', '');
        
        $products = Product::query()
            ->with('category')
            ->withCount('reviews')
            ->where('name', 'LIKE', "%{$keyword}%")
            ->orWhere('description', 'LIKE', "%{$keyword}%")
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }
}   
