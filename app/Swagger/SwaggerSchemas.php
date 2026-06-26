<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'MessageResponse',
    type: 'object',
    example: ['success' => true, 'message' => 'Logged out successfully']
)]
#[OA\Schema(
    schema: 'AuthResponse',
    type: 'object',
    example: [
        'success' => true,
        'message' => 'Logged in successfully',
        'user' => [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'is_admin' => false,
        ],
        'token' => '1|abc123token',
    ]
)]
#[OA\Schema(
    schema: 'ProfileResponse',
    type: 'object',
    example: [
        'success' => true,
        'user' => [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'is_admin' => false,
        ],
    ]
)]
#[OA\Schema(
    schema: 'LoginRequest',
    type: 'object',
    example: ['email' => 'john@example.com', 'password' => 'secret123']
)]
#[OA\Schema(
    schema: 'RegisterRequest',
    type: 'object',
    example: [
        'name' => 'John Doe',
        'email' => 'john@example.com',
        'password' => 'secret123',
        'password_confirmation' => 'secret123',
    ]
)]
#[OA\Schema(
    schema: 'UpdateProfileRequest',
    type: 'object',
    example: ['name' => 'John Doe', 'email' => 'john@example.com']
)]
#[OA\Schema(
    schema: 'ChangePasswordRequest',
    type: 'object',
    example: [
        'current_password' => 'oldsecret123',
        'password' => 'newsecret123',
        'password_confirmation' => 'newsecret123',
    ]
)]
#[OA\Schema(
    schema: 'CategoryResponse',
    type: 'object',
    example: [
        'success' => true,
        'data' => [
            [
                'id' => 1,
                'name' => 'Vegetables',
                'slug' => 'vegetables',
                'description' => 'Fresh market vegetables',
                'products' => [
                    [
                        'id' => 10,
                        'name' => 'Red Chili',
                        'slug' => 'red-chili',
                        'description' => 'Hot and fresh chili peppers',
                        'price' => 2.99,
                        'stock' => 50,
                        'image' => 'products/chili.jpg',
                    ],
                ],
            ],
        ],
    ]
)]
#[OA\Schema(
    schema: 'ProductPageResponse',
    type: 'object',
    example: [
        'success' => true,
        'data' => [
            'current_page' => 1,
            'data' => [
                [
                    'id' => 10,
                    'name' => 'Red Chili',
                    'slug' => 'red-chili',
                    'description' => 'Hot and fresh chili peppers',
                    'price' => 2.99,
                    'stock' => 50,
                    'image' => 'products/chili.jpg',
                    'category' => [
                        'id' => 1,
                        'name' => 'Vegetables',
                        'slug' => 'vegetables',
                    ],
                ],
            ],
            'per_page' => 12,
            'last_page' => 1,
            'total' => 1,
        ],
    ]
)]
#[OA\Schema(
    schema: 'ProductDetailResponse',
    type: 'object',
    example: [
        'success' => true,
        'data' => [
            'id' => 10,
            'name' => 'Red Chili',
            'slug' => 'red-chili',
            'description' => 'Hot and fresh chili peppers',
            'price' => 2.99,
            'stock' => 50,
            'image' => 'products/chili.jpg',
            'category' => [
                'id' => 1,
                'name' => 'Vegetables',
                'slug' => 'vegetables',
            ],
            'reviews' => [
                [
                    'id' => 5,
                    'rating' => 5,
                    'comment' => 'Fresh, tasty and delivered fast.',
                    'created_at' => '2026-06-26T10:00:00Z',
                    'user' => [
                        'id' => 2,
                        'name' => 'Jane Doe',
                        'email' => 'jane@example.com',
                    ],
                ],
            ],
        ],
    ]
)]
#[OA\Schema(
    schema: 'WishlistListResponse',
    type: 'object',
    example: [
        'success' => true,
        'data' => [
            [
                'id' => 201,
                'user_id' => 1,
                'product_id' => 10,
                'created_at' => '2026-06-26T10:00:00Z',
                'product' => [
                    'id' => 10,
                    'name' => 'Red Chili',
                    'slug' => 'red-chili',
                    'price' => 2.99,
                    'image' => 'products/chili.jpg',
                ],
            ],
        ],
    ]
)]
#[OA\Schema(
    schema: 'WishlistItemResponse',
    type: 'object',
    example: [
        'success' => true,
        'message' => 'Added to wishlist',
        'data' => [
            'id' => 201,
            'user_id' => 1,
            'product_id' => 10,
            'created_at' => '2026-06-26T10:00:00Z',
            'product' => [
                'id' => 10,
                'name' => 'Red Chili',
                'slug' => 'red-chili',
                'price' => 2.99,
                'image' => 'products/chili.jpg',
            ],
        ],
    ]
)]
#[OA\Schema(
    schema: 'CheckWishlistResponse',
    type: 'object',
    example: ['success' => true, 'in_wishlist' => true]
)]
#[OA\Schema(
    schema: 'OrderPageResponse',
    type: 'object',
    example: [
        'success' => true,
        'data' => [
            'current_page' => 1,
            'data' => [
                [
                    'id' => 101,
                    'total_price' => 25.5,
                    'status' => 'pending',
                    'shipping_address' => '123 Main St, Phnom Penh',
                    'created_at' => '2026-06-26T10:00:00Z',
                    'items_count' => 3,
                ],
            ],
            'per_page' => 10,
            'last_page' => 1,
            'total' => 1,
        ],
    ]
)]
#[OA\Schema(
    schema: 'OrderCreateRequest',
    type: 'object',
    example: [
        'items' => [
            ['product_id' => 10, 'quantity' => 2],
        ],
        'shipping_address' => '123 Main St, Phnom Penh',
        'notes' => 'Please call on arrival',
    ]
)]
#[OA\Schema(
    schema: 'OrderCreateResponse',
    type: 'object',
    example: [
        'success' => true,
        'message' => 'Order created successfully',
        'order' => [
            'id' => 101,
            'total_price' => 25.5,
            'status' => 'pending',
            'shipping_address' => '123 Main St, Phnom Penh',
            'created_at' => '2026-06-26T10:00:00Z',
            'items' => [
                [
                    'id' => 5001,
                    'order_id' => 101,
                    'product_id' => 10,
                    'quantity' => 2,
                    'price' => 2.99,
                ],
            ],
        ],
    ]
)]
#[OA\Schema(
    schema: 'OrderDetailResponse',
    type: 'object',
    example: [
        'success' => true,
        'data' => [
            'id' => 101,
            'total_price' => 25.5,
            'status' => 'pending',
            'shipping_address' => '123 Main St, Phnom Penh',
            'created_at' => '2026-06-26T10:00:00Z',
            'items' => [
                [
                    'id' => 5001,
                    'order_id' => 101,
                    'product_id' => 10,
                    'quantity' => 2,
                    'price' => 2.99,
                    'product' => [
                        'id' => 10,
                        'name' => 'Red Chili',
                        'slug' => 'red-chili',
                        'price' => 2.99,
                        'image' => 'products/chili.jpg',
                    ],
                ],
            ],
        ],
    ]
)]
#[OA\Schema(
    schema: 'ReviewPageResponse',
    type: 'object',
    example: [
        'success' => true,
        'data' => [
            'current_page' => 1,
            'data' => [
                [
                    'id' => 5,
                    'user_id' => 2,
                    'product_id' => 10,
                    'rating' => 5,
                    'comment' => 'Fresh, tasty and delivered fast.',
                    'created_at' => '2026-06-26T10:00:00Z',
                    'user' => [
                        'id' => 2,
                        'name' => 'Jane Doe',
                        'email' => 'jane@example.com',
                    ],
                ],
            ],
            'per_page' => 10,
            'last_page' => 1,
            'total' => 1,
        ],
    ]
)]
#[OA\Schema(
    schema: 'ReviewRequest',
    type: 'object',
    example: ['rating' => 5, 'comment' => 'Fresh, tasty and delivered fast.']
)]
#[OA\Schema(
    schema: 'ReviewResponse',
    type: 'object',
    example: [
        'success' => true,
        'message' => 'Review posted successfully',
        'data' => [
            'id' => 5,
            'user_id' => 2,
            'product_id' => 10,
            'rating' => 5,
            'comment' => 'Fresh, tasty and delivered fast.',
            'created_at' => '2026-06-26T10:00:00Z',
            'user' => [
                'id' => 2,
                'name' => 'Jane Doe',
                'email' => 'jane@example.com',
            ],
        ],
    ]
)]
class SwaggerSchemas
{
}
