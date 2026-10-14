<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FoodController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\RestaurantController;
use App\Http\Controllers\Api\VoucherApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API Routes for Foodie Web & Mobile Frontend
|--------------------------------------------------------------------------
*/

// Health check / API info
Route::get('/', function () {
    return response()->json([
        'status' => 'online',
        'service' => 'Foodie REST API',
        'version' => '1.0.0',
        'time' => now()->toIso8601String(),
    ]);
});

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::post('/categories', [CategoryController::class, 'store']);

// Foods / Menu
Route::get('/foods', [FoodController::class, 'index']);
Route::get('/foods/{id}', [FoodController::class, 'show']);
Route::post('/foods', [FoodController::class, 'store']);

// Partner Restaurants
Route::get('/restaurants', [RestaurantController::class, 'index']);

// Vouchers & Promo
Route::get('/vouchers', [VoucherApiController::class, 'list']);
Route::post('/vouchers/apply', [VoucherApiController::class, 'apply']);

// Newsletter Subscription
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe']);

// Orders / Checkout
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{orderId}', [OrderController::class, 'show']);
Route::post('/orders', [OrderController::class, 'store']);
Route::post('/orders/{orderId}/complete-midtrans', [OrderController::class, 'completeMidtrans']);
Route::post('/orders/{orderId}/mark-as-paid', [OrderController::class, 'markAsPaid']);
Route::get('/orders/{orderId}/receipt', [OrderController::class, 'receipt']);
