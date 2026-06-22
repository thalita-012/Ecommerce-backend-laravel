<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\WishlistController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ReviewController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {
    // Public Routes (No Authentication Required)
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{product}', [ProductController::class, 'show']);
    Route::get('/products/search', [ProductController::class, 'search']);

    // Auth Routes (Public)
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::get('/products/{product}/reviews', [ReviewController::class, 'index']);

    // Protected Auth Routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/profile', [AuthController::class, 'profile']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::put('/auth/profile', [AuthController::class, 'updateProfile']);
        Route::post('/auth/change-password', [AuthController::class, 'changePassword']);

        Route::get('/wishlist', [WishlistController::class, 'index']);
        Route::post('/wishlist', [WishlistController::class, 'store']);
        Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'destroy']);
        Route::get('/wishlist/check/{productId}', [WishlistController::class, 'check']);

        Route::get('/orders', [OrderController::class, 'index']);
        Route::post('/orders', [OrderController::class, 'store']);
        Route::get('/orders/{order}', [OrderController::class, 'show']);

        Route::post('/products/{product}/reviews', [ReviewController::class, 'store']);
        Route::put('/reviews/{review}', [ReviewController::class, 'update']);
        Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
    });
});
