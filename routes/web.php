<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// ============================================
// USER ROUTES (FRONTEND)
// ============================================

// Trang chủ
Route::get('/', [UserController::class, 'home'])->name('home');

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

// ============================================
// ADMIN ROUTES
// ============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])
        ->name('dashboard');

    // Quản lý Users - CRUD
    Route::get('/users', [AdminController::class, 'users'])
        ->name('users');
    Route::get('/users/create', [AdminController::class, 'createUser'])
        ->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])
        ->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])
        ->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])
        ->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])
        ->name('users.destroy');

    // Redirect admin root to dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
});
