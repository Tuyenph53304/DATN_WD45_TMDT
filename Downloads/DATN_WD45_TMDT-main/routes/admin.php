<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminVoucherController;
use App\Http\Controllers\Admin\AdminBannerController;
use App\Http\Controllers\Admin\AdminNewsController;
use Illuminate\Support\Facades\Route;

// ============================================
// ADMIN ROUTES
// ============================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    // Quản lý Users
    Route::resource('users', AdminUserController::class);

    // Quản lý Products
    Route::resource('products', AdminProductController::class);

    // Quản lý Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{order}/confirm', [AdminOrderController::class, 'confirmOrder'])->name('orders.confirm');
    Route::post('/orders/{order}/confirm-cancel', [AdminOrderController::class, 'confirmCancel'])->name('orders.confirmCancel');
    Route::delete('/orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy');

    // Quản lý Categories
    Route::resource('categories', AdminCategoryController::class);

    // Quản lý Vouchers
    Route::resource('vouchers', AdminVoucherController::class);

    // Quản lý Banners
    Route::resource('banners', AdminBannerController::class);

    // Quản lý News
    Route::resource('news', AdminNewsController::class);
});
