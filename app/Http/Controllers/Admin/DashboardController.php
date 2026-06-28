<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        // Keep dashboard data lightweight by loading only summary stats here.
        // This avoids doing model queries inside Blade and prevents accidental
        // N+1 queries when the dashboard renders recent records.
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();
        $totalUsers = User::where('is_admin', false)->count();

        // Fetch only the columns needed for the recent-orders widget.
        $recentOrders = Order::query()
            ->select(['id', 'user_id', 'total_price', 'status', 'created_at'])
            ->with('user:id,name')
            ->latest()
            ->limit(5)
            ->get();

        // Fetch only the fields needed for the top-products widget.
        $topProducts = Product::query()
            ->select(['id', 'name', 'price', 'stock', 'created_at'])
            ->latest()
            ->limit(5)
            ->get();

        // Fetch low stock items for admin restock alerts (stock <= 5).
        $lowStockProducts = Product::query()
            ->select(['id', 'name', 'stock', 'price'])
            ->where('stock', '<=', 5)
            ->orderBy('stock', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalCategories',
            'totalOrders',
            'totalUsers',
            'recentOrders',
            'topProducts',
            'lowStockProducts'
        ));
    }
}
