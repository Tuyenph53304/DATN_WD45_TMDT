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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shipping_address_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('total_price', 10, 2);
            // Status của đơn hàng - 7 trạng thái:
            // 1. pending_confirmation: Chờ xác nhận (trạng thái ban đầu sau khi thanh toán)
            //    → có thể chuyển sang: confirmed (admin xác nhận), cancelled (khách hủy)
            // 2. confirmed: Đã xác nhận (admin đã xác nhận đơn hàng)
            //    → có thể chuyển sang: shipping (vận chuyển đến)
            // 3. shipping: Đang giao hàng (đơn hàng đang được vận chuyển)
            //    → có thể chuyển sang: delivered (đã giao hàng)
            // 4. delivered: Đã giao hàng (đơn hàng đã đến tay khách hàng)
            //    → có thể chuyển sang: completed (khách xác nhận nhận hàng), delivery_failed (khách hoàn hàng)
            // 5. completed: Thành công (KHÔNG THỂ THAY ĐỔI - đóng luồng mua hàng)
            // 6. cancelled: Đã hủy (KHÔNG THỂ THAY ĐỔI - đóng luồng mua hàng)
            // 7. delivery_failed: Giao hàng không thành công (KHÔNG THỂ THAY ĐỔI - đóng luồng mua hàng)
            $table->string('status')->default('pending_confirmation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
