<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// ============================================
// USER ROUTES (FRONTEND)
// ============================================

// Trang chủ
Route::get('/', [UserController::class, 'home'])->name('home');

// Sản phẩm
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Giỏ hàng
Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
});

// API routes cho giỏ hàng (có thể dùng không cần auth nếu cần)
Route::post('/api/cart/add', [CartController::class, 'add'])->name('api.cart.add');
Route::put('/api/cart/{id}', [CartController::class, 'update'])->name('api.cart.update');
Route::delete('/api/cart/{id}', [CartController::class, 'remove'])->name('api.cart.remove');

// Voucher routes
Route::post('/api/voucher/validate', [VoucherController::class, 'validate'])->name('api.voucher.validate');
Route::post('/voucher/apply', [VoucherController::class, 'apply'])->name('voucher.apply');
Route::post('/voucher/remove', [VoucherController::class, 'remove'])->name('voucher.remove');

// Payment routes
Route::middleware('auth')->group(function () {
    Route::post('/payment/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success/{order}', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/fail', [PaymentController::class, 'fail'])->name('payment.fail');
});
Route::get('/payment/callback', [PaymentController::class, 'callback'])->name('payment.callback');
Route::post('/payment/ipn', [PaymentController::class, 'ipn'])->name('payment.ipn'); // MoMo IPN

// User Profile routes
Route::middleware('auth')->prefix('user')->name('user.')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/orders', [UserController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [UserController::class, 'orderDetail'])->name('orders.show');

    // Shipping Address routes
    Route::post('/shipping-address', [UserController::class, 'storeShippingAddress'])->name('shipping-address.store');
    Route::put('/shipping-address/{id}', [UserController::class, 'updateShippingAddress'])->name('shipping-address.update');
    Route::delete('/shipping-address/{id}', [UserController::class, 'deleteShippingAddress'])->name('shipping-address.delete');
    Route::post('/shipping-address/{id}/set-default', [UserController::class, 'setDefaultShippingAddress'])->name('shipping-address.set-default');
});
