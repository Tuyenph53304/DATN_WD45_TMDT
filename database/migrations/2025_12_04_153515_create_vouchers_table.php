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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Mã voucher
            $table->string('name')->nullable(); // Tên voucher
            $table->enum('type', ['percentage', 'fixed'])->default('percentage'); // Loại: phần trăm hoặc số tiền cố định
            $table->decimal('value', 10, 2); // Giá trị giảm (phần trăm hoặc số tiền)
            $table->decimal('min_order', 10, 2)->default(0); // Đơn hàng tối thiểu
            $table->decimal('max_discount', 10, 2)->nullable(); // Giảm tối đa (nếu là percentage)
            $table->unsignedInteger('usage_limit')->nullable(); // Số lần sử dụng tối đa
            $table->unsignedInteger('used_count')->default(0); // Số lần đã sử dụng
            $table->dateTime('start_date'); // Ngày bắt đầu
            $table->dateTime('end_date'); // Ngày kết thúc
            $table->boolean('status')->default(true); // Trạng thái
            $table->text('description')->nullable(); // Mô tả
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
