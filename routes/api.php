<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
  Route::get('products', [ProductController::class, 'index']);
  Route::get('products/{product}', [ProductController::class, 'show']);

  Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', [MeController::class, 'index']);
    Route::apiResource("products", ProductController::class)->except(['index', 'show']);
    Route::apiResource("carts", CartController::class)->except(['show']);
    Route::post('orders', [OrderController::class, 'store']);
  });
});
