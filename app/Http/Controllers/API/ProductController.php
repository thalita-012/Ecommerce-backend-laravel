<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get all products with pagination
     */
    public function index(Request $request)
    {
        $query = Product::with('category', 'reviews');

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
     * Get product details
     */
    public function show(Product $product)
    {
        $product->load('category', 'reviews.user');
        
        return response()->json([
            'success' => true,
            'data' => $product,
        ]);
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $keyword = $request->get('q', '');
        
        $products = Product::with('category')
            ->where('name', 'LIKE', "%{$keyword}%")
            ->orWhere('description', 'LIKE', "%{$keyword}%")
            ->paginate(12);

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }
}   