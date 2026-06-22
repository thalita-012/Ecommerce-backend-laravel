<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get all categories
     */
    public function index()
    {
        $categories = Category::with('products')->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Get specific category with products
     */
    public function show(Category $category)
    {
        $category->load('products');
        
        return response()->json([
            'success' => true,
            'data' => $category,
        ]);
    }
}