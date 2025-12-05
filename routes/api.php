<?php

use App\Http\Controllers\Admin\AdminBannerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ============================================
// API ROUTES
// ============================================

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Banner API routes
Route::post('/banners/{banner}/toggle-active', [AdminBannerController::class, 'toggleActive'])->middleware('auth', 'admin');

