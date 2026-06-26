<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class CategoryController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     operationId="listCategories",
     *     tags={"Categories"},
     *     summary="Get all categories",
     *     @OA\Response(
     *         response=200,
     *         description="Category list",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryResponse")
     *     )
     * )
     */
    public function index()
    {
        $categories = Category::query()
            ->select(['id', 'name', 'slug', 'description'])
            ->withCount('products')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{category}",
     *     operationId="showCategory",
     *     tags={"Categories"},
     *     summary="Get a single category with its products",
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         description="Category ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Category details",
     *         @OA\JsonContent(ref="#/components/schemas/CategoryResponse")
     *     ),
     *     @OA\Response(response=404, description="Category not found")
     * )
     */
    public function show(Category $category)
    {
        $category->load([
            'products' => function ($query) {
                $query->select(['id', 'name', 'slug', 'description', 'price', 'stock', 'image', 'category_id'])
                    ->latest();
            },
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }
}
