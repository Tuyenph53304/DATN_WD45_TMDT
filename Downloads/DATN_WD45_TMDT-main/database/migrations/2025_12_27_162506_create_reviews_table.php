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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete(); // Đơn hàng đã mua
            $table->integer('rating')->default(5); // Đánh giá từ 1-5 sao
            $table->text('comment')->nullable(); // Bình luận
            $table->boolean('status')->default(true); // Trạng thái (true = hiển thị, false = ẩn)
            $table->timestamps();
            
            // Đảm bảo mỗi user chỉ đánh giá 1 lần cho 1 sản phẩm trong 1 đơn hàng
            $table->unique(['user_id', 'product_id', 'order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
