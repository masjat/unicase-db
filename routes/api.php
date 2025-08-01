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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\CustomCaseController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\BrandTypeController;

Route::get('/rajaongkir/provinces', [RajaOngkirController::class, 'provinces']);
Route::get('/rajaongkir/cities', [RajaOngkirController::class, 'cities']); // ?province_id=5
Route::post('/rajaongkir/services', [RajaOngkirController::class, 'courierServices']);



Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/request-reset', [AuthController::class, 'requestReset']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);
Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);
Route::post('/ordertracking', [OrderTrackingController::class, 'store']);




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
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::put('/payments/{id}', [PaymentController::class, 'update']);
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);
    Route::apiResource('custom-cases', CustomCaseController::class);
    Route::apiResource('wishlist', WishlistController::class);


});

Route::middleware(['auth:sanctum', IsAdmin::class])->group(function () {
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::apiResource('categories', CategoryController::class);
    Route::get('/products/{productId}/images', [ProductImageController::class, 'index']);
    Route::post('/product-images', [ProductImageController::class, 'store']);
    Route::delete('/product-images/{id}', [ProductImageController::class, 'destroy']);
    Route::apiResource('payment-methods', PaymentMethodController::class);
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::put('/payments/{id}/confirm', [PaymentController::class, 'confirmPayment']);
    Route::apiResource('brands', BrandController::class);
    Route::apiResource('brand-types', BrandTypeController::class);

});

