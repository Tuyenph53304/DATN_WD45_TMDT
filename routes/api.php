<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ============================================
// API ROUTES
// ============================================

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Thêm các API routes khác ở đây
// Ví dụ:
// Route::prefix('v1')->group(function () {
//     Route::apiResource('products', ProductController::class);
//     Route::apiResource('orders', OrderController::class);
// });

