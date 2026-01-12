<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status'); // vnpay, momo, cod, etc.
            $table->string('payment_status')->default('pending')->after('payment_method'); // pending, paid, failed, refunded
            $table->string('transaction_id')->nullable()->after('payment_status'); // Mã giao dịch từ cổng thanh toán
            $table->string('voucher_code')->nullable()->after('transaction_id'); // Mã voucher đã sử dụng
            $table->decimal('discount_amount', 10, 2)->default(0)->after('voucher_code'); // Số tiền giảm từ voucher
            $table->decimal('final_amount', 10, 2)->after('discount_amount'); // Tổng tiền cuối cùng sau khi giảm
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method',
                'payment_status',
                'transaction_id',
                'voucher_code',
                'discount_amount',
                'final_amount'
            ]);
        });
    }
};
