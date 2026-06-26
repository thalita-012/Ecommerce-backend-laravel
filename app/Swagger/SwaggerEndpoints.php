<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class SwaggerEndpoints
{
    #[OA\Get(
        path: '/api/categories',
        operationId: 'listCategories',
        tags: ['Categories'],
        summary: 'Get all categories',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Category list',
                content: new OA\JsonContent(ref: '#/components/schemas/CategoryResponse')
            ),
        ]
    )]
    public function categoriesIndex(): void
    {
    }

    #[OA\Get(
        path: '/api/categories/{category}',
        operationId: 'showCategory',
        tags: ['Categories'],
        summary: 'Get a single category with its products',
        parameters: [
            new OA\Parameter(
                name: 'category',
                in: 'path',
                required: true,
                description: 'Category ID',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Category details',
                content: new OA\JsonContent(ref: '#/components/schemas/CategoryResponse')
            ),
        ]
    )]
    public function categoriesShow(): void
    {
    }

    #[OA\Get(
        path: '/api/products',
        operationId: 'listProducts',
        tags: ['Products'],
        summary: 'Get paginated products',
        parameters: [
            new OA\Parameter(
                name: 'category',
                in: 'query',
                required: false,
                description: 'Filter by category slug',
                schema: new OA\Schema(type: 'string', example: 'vegetables')
            ),
            new OA\Parameter(
                name: 'min_price',
                in: 'query',
                required: false,
                description: 'Minimum price',
                schema: new OA\Schema(type: 'number', format: 'float', example: 1)
            ),
            new OA\Parameter(
                name: 'max_price',
                in: 'query',
                required: false,
                description: 'Maximum price',
                schema: new OA\Schema(type: 'number', format: 'float', example: 100)
            ),
            new OA\Parameter(
                name: 'sort',
                in: 'query',
                required: false,
                description: 'Sort order',
                schema: new OA\Schema(type: 'string', enum: ['latest', 'price-asc', 'price-desc'], example: 'latest')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated products',
                content: new OA\JsonContent(ref: '#/components/schemas/ProductPageResponse')
            ),
        ]
    )]
    public function productsIndex(): void
    {
    }

    #[OA\Get(
        path: '/api/products/search',
        operationId: 'searchProducts',
        tags: ['Products'],
        summary: 'Search products by keyword',
        parameters: [
            new OA\Parameter(
                name: 'q',
                in: 'query',
                required: false,
                description: 'Search keyword',
                schema: new OA\Schema(type: 'string', example: 'chili')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Search results',
                content: new OA\JsonContent(ref: '#/components/schemas/ProductPageResponse')
            ),
        ]
    )]
    public function productsSearch(): void
    {
    }

    #[OA\Get(
        path: '/api/products/{product}',
        operationId: 'showProduct',
        tags: ['Products'],
        summary: 'Get full product details',
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer', example: 10)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Product details',
                content: new OA\JsonContent(ref: '#/components/schemas/ProductDetailResponse')
            ),
        ]
    )]
    public function productsShow(): void
    {
    }

    #[OA\Post(
        path: '/api/auth/register',
        operationId: 'registerUser',
        tags: ['Auth'],
        summary: 'Register a new user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/RegisterRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'User registered successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/AuthResponse')
            ),
        ]
    )]
    public function authRegister(): void
    {
    }

    #[OA\Post(
        path: '/api/auth/login',
        operationId: 'loginUser',
        tags: ['Auth'],
        summary: 'Login an existing user',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/LoginRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(ref: '#/components/schemas/AuthResponse')
            ),
        ]
    )]
    public function authLogin(): void
    {
    }

    #[OA\Get(
        path: '/api/auth/profile',
        operationId: 'getProfile',
        tags: ['Auth'],
        summary: 'Get current profile',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Authenticated user profile',
                content: new OA\JsonContent(ref: '#/components/schemas/ProfileResponse')
            ),
        ]
    )]
    public function authProfile(): void
    {
    }

    #[OA\Put(
        path: '/api/auth/profile',
        operationId: 'updateProfile',
        tags: ['Auth'],
        summary: 'Update profile',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/UpdateProfileRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profile updated',
                content: new OA\JsonContent(ref: '#/components/schemas/ProfileResponse')
            ),
        ]
    )]
    public function authUpdateProfile(): void
    {
    }

    #[OA\Post(
        path: '/api/auth/change-password',
        operationId: 'changePassword',
        tags: ['Auth'],
        summary: 'Change account password',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ChangePasswordRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Password changed successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
        ]
    )]
    public function authChangePassword(): void
    {
    }

    #[OA\Post(
        path: '/api/auth/logout',
        operationId: 'logoutUser',
        tags: ['Auth'],
        summary: 'Logout current session',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Logout successful',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
        ]
    )]
    public function authLogout(): void
    {
    }

    #[OA\Get(
        path: '/api/wishlist',
        operationId: 'listWishlist',
        tags: ['Wishlist'],
        summary: 'Get the authenticated user\'s wishlist',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Wishlist list',
                content: new OA\JsonContent(ref: '#/components/schemas/WishlistListResponse')
            ),
        ]
    )]
    public function wishlistIndex(): void
    {
    }

    #[OA\Post(
        path: '/api/wishlist',
        operationId: 'addWishlist',
        tags: ['Wishlist'],
        summary: 'Add a product to the wishlist',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['product_id'],
                properties: [
                    new OA\Property(property: 'product_id', type: 'integer', example: 10),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Added to wishlist',
                content: new OA\JsonContent(ref: '#/components/schemas/WishlistItemResponse')
            ),
        ]
    )]
    public function wishlistStore(): void
    {
    }

    #[OA\Delete(
        path: '/api/wishlist/{wishlist}',
        operationId: 'removeWishlist',
        tags: ['Wishlist'],
        summary: 'Remove an item from the wishlist',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'wishlist',
                in: 'path',
                required: true,
                description: 'Wishlist item ID',
                schema: new OA\Schema(type: 'integer', example: 201)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Removed from wishlist',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
        ]
    )]
    public function wishlistDestroy(): void
    {
    }

    #[OA\Get(
        path: '/api/wishlist/check/{productId}',
        operationId: 'checkWishlist',
        tags: ['Wishlist'],
        summary: 'Check if a product is in the wishlist',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'productId',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer', example: 10)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Wishlist status',
                content: new OA\JsonContent(ref: '#/components/schemas/CheckWishlistResponse')
            ),
        ]
    )]
    public function wishlistCheck(): void
    {
    }

    #[OA\Get(
        path: '/api/orders',
        operationId: 'listOrders',
        tags: ['Orders'],
        summary: 'Get paginated order history',
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated order list',
                content: new OA\JsonContent(ref: '#/components/schemas/OrderPageResponse')
            ),
        ]
    )]
    public function ordersIndex(): void
    {
    }

    #[OA\Post(
        path: '/api/orders',
        operationId: 'createOrder',
        tags: ['Orders'],
        summary: 'Create a new order',
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/OrderCreateRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Order created successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/OrderCreateResponse')
            ),
        ]
    )]
    public function ordersStore(): void
    {
    }

    #[OA\Get(
        path: '/api/orders/{order}',
        operationId: 'showOrder',
        tags: ['Orders'],
        summary: 'Get order details',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'order',
                in: 'path',
                required: true,
                description: 'Order ID',
                schema: new OA\Schema(type: 'integer', example: 101)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Order details',
                content: new OA\JsonContent(ref: '#/components/schemas/OrderDetailResponse')
            ),
        ]
    )]
    public function ordersShow(): void
    {
    }

    #[OA\Get(
        path: '/api/products/{product}/reviews',
        operationId: 'listProductReviews',
        tags: ['Reviews'],
        summary: 'Get reviews for a product',
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer', example: 10)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Paginated product reviews',
                content: new OA\JsonContent(ref: '#/components/schemas/ReviewPageResponse')
            ),
        ]
    )]
    public function reviewsIndex(): void
    {
    }

    #[OA\Post(
        path: '/api/products/{product}/reviews',
        operationId: 'createProductReview',
        tags: ['Reviews'],
        summary: 'Create a review for a purchased product',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'product',
                in: 'path',
                required: true,
                description: 'Product ID',
                schema: new OA\Schema(type: 'integer', example: 10)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ReviewRequest')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Review posted successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/ReviewResponse')
            ),
        ]
    )]
    public function reviewsStore(): void
    {
    }

    #[OA\Put(
        path: '/api/reviews/{review}',
        operationId: 'updateReview',
        tags: ['Reviews'],
        summary: 'Update an existing review',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'review',
                in: 'path',
                required: true,
                description: 'Review ID',
                schema: new OA\Schema(type: 'integer', example: 5)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ReviewRequest')
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Review updated successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/ReviewResponse')
            ),
        ]
    )]
    public function reviewsUpdate(): void
    {
    }

    #[OA\Delete(
        path: '/api/reviews/{review}',
        operationId: 'deleteReview',
        tags: ['Reviews'],
        summary: 'Delete a review',
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'review',
                in: 'path',
                required: true,
                description: 'Review ID',
                schema: new OA\Schema(type: 'integer', example: 5)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Review deleted successfully',
                content: new OA\JsonContent(ref: '#/components/schemas/MessageResponse')
            ),
        ]
    )]
    public function reviewsDestroy(): void
    {
    }
}
