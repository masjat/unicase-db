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
use App\Http\Controllers\ProductImageController;
use App\Http\Controllers\RajaOngkirController;

Route::get('/rajaongkir/provinces', [RajaOngkirController::class, 'provinces']);
Route::get('/rajaongkir/cities', [RajaOngkirController::class, 'cities']); // ?province_id=5
Route::post('/rajaongkir/services', [RajaOngkirController::class, 'courierServices']);



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);
Route::post('/ordertracking', [OrderTrackingController::class, 'store']);
Route::apiResource('shipping-options', ShippingOptionController::class);




Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('review', ReviewController::class);
    Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);
    Route::apiResource('wishlist',WishlistController::class);
    Route::apiResource('shipping-addresses', ShippingAddressController::class);
    Route::post('/checkouts', [CheckoutController::class, 'store']);
    Route::get('/cart', [ShippingCartController::class, 'index']);
    Route::post('/cart', [ShippingCartController::class, 'store']);
    Route::put('/cart/{id}', [ShippingCartController::class, 'update']);
    Route::delete('/cart/{id}', [ShippingCartController::class, 'destroy']);


});

Route::middleware(['auth:sanctum', IsAdmin::class])->group(function () {
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::apiResource('categories', CategoryController::class);
    Route::get('/products/{productId}/images', [ProductImageController::class, 'index']);
    Route::post('/product-images', [ProductImageController::class, 'store']);
    Route::delete('/product-images/{id}', [ProductImageController::class, 'destroy']);
    Route::apiResource('payment-methods', PaymentMethodController::class);

});

