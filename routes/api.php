<?php

use App\Http\Controllers\Admin\AdminNewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ============================================
// API ROUTES
// ============================================

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// News API routes
Route::post('/news/{news}/toggle-published', [AdminNewsController::class, 'togglePublished'])->middleware('auth', 'admin');

