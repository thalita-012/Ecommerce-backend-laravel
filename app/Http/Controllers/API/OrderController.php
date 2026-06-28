<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use OpenApi\Annotations as OA;

class OrderController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/orders",
     *     operationId="createOrder",
     *     tags={"Orders"},
     *     summary="Create a new order",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/OrderCreateRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/OrderCreateResponse")
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation or stock error",
     *         @OA\JsonContent(ref="#/components/schemas/ErrorResponse")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required',
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

            $shippingAddressStr = is_array($validated['shipping_address']) 
                ? json_encode($validated['shipping_address']) 
                : (string) $validated['shipping_address'];

            // Create order
            $order = $request->user()->orders()->create([
                'total_price' => $totalPrice,
                'status' => 'pending',
                'shipping_address' => $shippingAddressStr,
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
     * @OA\Get(
     *     path="/api/orders",
     *     operationId="listOrders",
     *     tags={"Orders"},
     *     summary="Get paginated order history",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Paginated order list",
     *         @OA\JsonContent(ref="#/components/schemas/OrderPageResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(Request $request)
    {
        $orders = $request->user()
            ->orders()
            ->select(['id', 'user_id', 'total_price', 'status', 'created_at'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/orders/{order}",
     *     operationId="showOrder",
     *     tags={"Orders"},
     *     summary="Get order details",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer", example=101)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order details",
     *         @OA\JsonContent(ref="#/components/schemas/OrderDetailResponse")
     *     ),
     *     @OA\Response(response=404, description="Order not found"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
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
