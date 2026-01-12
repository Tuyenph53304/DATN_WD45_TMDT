<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tăng precision cho các cột tiền tệ từ decimal(10,2) lên decimal(15,2)
            // Để có thể lưu giá trị lên đến 999,999,999,999,999.99 (gần 1000 tỷ)
            DB::statement('ALTER TABLE `orders` MODIFY `total_price` DECIMAL(15, 2) NOT NULL');
            DB::statement('ALTER TABLE `orders` MODIFY `discount_amount` DECIMAL(15, 2) DEFAULT 0');
            DB::statement('ALTER TABLE `orders` MODIFY `final_amount` DECIMAL(15, 2) NOT NULL');
        });

        // Cũng cần tăng precision cho order_items và product_variants
        Schema::table('order_items', function (Blueprint $table) {
            DB::statement('ALTER TABLE `order_items` MODIFY `price` DECIMAL(15, 2) NOT NULL');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            DB::statement('ALTER TABLE `product_variants` MODIFY `price` DECIMAL(15, 2) NOT NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            DB::statement('ALTER TABLE `orders` MODIFY `total_price` DECIMAL(10, 2) NOT NULL');
            DB::statement('ALTER TABLE `orders` MODIFY `discount_amount` DECIMAL(10, 2) DEFAULT 0');
            DB::statement('ALTER TABLE `orders` MODIFY `final_amount` DECIMAL(10, 2) NOT NULL');
        });

        Schema::table('order_items', function (Blueprint $table) {
            DB::statement('ALTER TABLE `order_items` MODIFY `price` DECIMAL(10, 2) NOT NULL');
        });

        Schema::table('product_variants', function (Blueprint $table) {
            DB::statement('ALTER TABLE `product_variants` MODIFY `price` DECIMAL(10, 2) NOT NULL');
        });
    }
};
