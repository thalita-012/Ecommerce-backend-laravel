<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('admin/login');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Login Routes (Guest only)
    Route::middleware('guest')->group(function () {
        Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
        Route::post('login', [AdminAuthController::class, 'login']);
    });

    // Protected Admin Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

        // Dashboard
        Route::get('dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');

        // Categories CRUD
        Route::resource('categories', CategoryController::class, ['as' => 'admin']);

        // Products CRUD
        Route::resource('products', ProductController::class, ['as' => 'admin']);

        // Orders
        Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');

        // Users
        Route::get('users', [UserController::class, 'index'])->name('admin.users.index');
    });
});
