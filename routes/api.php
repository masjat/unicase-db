<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ShippingCartController;
use App\Http\Controllers\ShippingAddressController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);
Route::post('/ordertracking', [OrderTrackingController::class, 'store']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('review', ReviewController::class);
    Route::apiResource('wishlist',WishlistController::class);
    Route::apiResource('shippingcart', ShippingController::class);
    Route::apiResource('shipping-options', ShippingOptionController::class);
    Route::apiResource('status', StatusController::class);
    Route::apiResource('shipping-addresses', ShippingAddressController::class);


});
