<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Create new order (checkout)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            $totalPrice = 0;
            $orderItems = [];

            // Validate stock and calculate total
            foreach ($validated['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Not enough stock for {$product->name}");
                }

                $subtotal = $product->price * $item['quantity'];
                $totalPrice += $subtotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];

                // Reduce stock
                $product->decrement('stock', $item['quantity']);
            }

            // Create order
            $order = $request->user()->orders()->create([
                'total_price' => $totalPrice,
                'status' => 'pending',
                'shipping_address' => $validated['shipping_address'],
            ]);

            // Create order items
            foreach ($orderItems as $item) {
                $order->items()->create($item);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'order' => $order->load([
                    'items' => function ($query) {
                        $query->select(['id', 'order_id', 'product_id', 'quantity', 'price']);
                    },
                ]),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Get user's orders
     */
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->select(['id', 'user_id', 'total_price', 'status', 'shipping_address', 'created_at'])
            ->withCount('items')
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Get order details
     */
    public function show(Request $request, Order $order)
    {
        $order = $request->user()
            ->orders()
            ->with([
                'items' => function ($query) {
                    $query->select(['id', 'order_id', 'product_id', 'quantity', 'price']);
                },
                'items.product' => function ($query) {
                    $query->select(['id', 'name', 'slug', 'price', 'image']);
                },
            ])
            ->select(['id', 'user_id', 'total_price', 'status', 'shipping_address', 'created_at'])
            ->findOrFail($order->id);

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }
}
