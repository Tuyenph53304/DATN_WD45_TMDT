<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ============================================
// USER ROUTES (FRONTEND)
// ============================================

// Trang chủ
Route::get('/', [UserController::class, 'home'])->name('home');

// Thêm các route frontend khác ở đây
// Ví dụ:
// Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
