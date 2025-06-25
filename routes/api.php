<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\WishlistController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);
Route::resource('reviews', ReviewController::class);
Route::apiResource('addresses', AddressController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('payments', PaymentController::class);
Route::apiResource('order-details', OrderDetailController::class);
Route::apiResource('wishlists', WishlistController::class);




Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});
