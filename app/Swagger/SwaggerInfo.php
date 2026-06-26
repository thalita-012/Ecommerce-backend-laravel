<?php

namespace App\Swagger;

use OpenApi\Attributes as OAT;
use OpenApi\Annotations as OA;

#[OAT\Info(
    title: 'Ecommerce API Documentation',
    version: '1.0.0',
    description: 'Swagger/OpenAPI documentation for the Laravel ecommerce backend.'
)]
#[OAT\Server(
    url: 'http://localhost:8000',
    description: 'Local development server'
)]
#[OAT\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'Token',
    description: 'Use the token returned from login or register in the Authorization header as Bearer <token>.'
)]
#[OAT\Tag(name: 'Auth', description: 'Authentication and profile endpoints')]
#[OAT\Tag(name: 'Categories', description: 'Category listing endpoints')]
#[OAT\Tag(name: 'Products', description: 'Product browsing and detail endpoints')]
#[OAT\Tag(name: 'Wishlist', description: 'Wishlist management endpoints')]
#[OAT\Tag(name: 'Orders', description: 'Order history and checkout endpoints')]
#[OAT\Tag(name: 'Reviews', description: 'Product review endpoints')]
/**
 * @OA\Info(
 *     title="Ecommerce API Documentation",
 *     version="1.0.0",
 *     description="Swagger/OpenAPI documentation for the Laravel ecommerce backend."
 * )
 *
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Local development server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token",
 *     description="Use the token returned from login or register in the Authorization header as Bearer <token>."
 * )
 *
 * @OA\Tag(name="Auth", description="Authentication and profile endpoints")
 * @OA\Tag(name="Categories", description="Category listing endpoints")
 * @OA\Tag(name="Products", description="Product browsing and detail endpoints")
 * @OA\Tag(name="Wishlist", description="Wishlist management endpoints")
 * @OA\Tag(name="Orders", description="Order history and checkout endpoints")
 * @OA\Tag(name="Reviews", description="Product review endpoints")
 */
class SwaggerInfo
{
}
