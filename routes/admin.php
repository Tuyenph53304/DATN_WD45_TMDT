<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

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

