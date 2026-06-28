<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderController extends Controller
{
    public function index()
    {
        // Load only summary data for the list page.
        // The admin can open a single order to fetch full line-item details.
        $orders = Order::query()
            ->select(['id', 'user_id', 'total_price', 'status', 'created_at'])
            ->with('user:id,name')
            ->withCount('items')
            ->latest()
            ->paginate(10);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        // Load only the selected order with selective columns to prevent
        // hydrating full nested product rows or unnecessary user attributes.
        $order->load([
            'user:id,name,email',
            'items' => function ($query) {
                $query->select(['id', 'order_id', 'product_id', 'quantity', 'price'])
                    ->with('product:id,name,slug,image,price');
            },
        ]);
        return view('admin.orders.show', compact('order'));
    }
}
