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
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ShippingOptionController;
use App\Http\Middleware\IsAdmin;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);
Route::post('/ordertracking', [OrderTrackingController::class, 'store']);
Route::get('/payment-methods', [PaymentMethodController::class, 'index']);
Route::post('/payment-methods', [PaymentMethodController::class, 'store']);
Route::apiResource('shipping-options', ShippingOptionController::class);



Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('review', ReviewController::class);
    Route::apiResource('wishlist',WishlistController::class);
    Route::apiResource('shipping-addresses', ShippingAddressController::class);
    Route::post('/checkouts', [CheckoutController::class, 'store']);
    Route::get('/cart', [ShippingCartController::class, 'index']);
    Route::post('/cart', [ShippingCartController::class, 'store']);
    Route::put('/cart/{id}', [ShippingCartController::class, 'update']);
    Route::delete('/cart/{id}', [ShippingCartController::class, 'destroy']);


});

Route::middleware(['auth:sanctum', IsAdmin::class])->group(function () {
    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);

});

