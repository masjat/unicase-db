<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CustomController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\OrderTrackingController;
use App\Http\Controllers\StatusController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::resource('products', ProductController::class);
Route::resource('categories', CategoryController::class);
Route::get('/products/{productId}/reviews', [ReviewController::class, 'index']);
Route::post('/ordertracking', [OrderTrackingController::class, 'store']);
Route::apiResource('status', StatusController::class);
Route::post('/shipping', [ShippingController::class, 'store']);
Route::resource('reviews', ReviewController::class);
Route::post('/customcases', [CustomController::class, 'store']);
Route::get('/orders', [OrderController::class, 'index']);
Route::middleware('auth:sanctum')->group(function () {
Route::post('/logout', [AuthController::class, 'logout']);
});
