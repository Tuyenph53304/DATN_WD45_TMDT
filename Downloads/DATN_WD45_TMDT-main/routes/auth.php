<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// ============================================
// AUTHENTICATION ROUTES
// ============================================

// Routes đăng ký
Route::get('/register', [AuthController::class, 'showRegisterForm'])
    ->name('register')
    ->middleware('guest');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.post')
    ->middleware('guest');

// Routes đăng nhập
Route::get('/login', [AuthController::class, 'showLoginForm'])
    ->name('login')
    ->middleware('guest');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.post')
    ->middleware('guest');

// Route đăng xuất
Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

