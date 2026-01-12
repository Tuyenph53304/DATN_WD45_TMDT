<?php

namespace Database\Seeders;

use App\Models\Voucher;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    public function run(): void
    {
        // Voucher giảm 10% - không giới hạn
        Voucher::create([
            'code' => 'GIAM10',
            'name' => 'Giảm 10% cho đơn hàng bất kỳ',
            'type' => 'percentage',
            'value' => 10,
            'min_order' => 0,
            'max_discount' => 500000, // Giảm tối đa 500,000₫
            'usage_limit' => null, // Không giới hạn
            'used_count' => 0,
            'start_date' => Carbon::now()->subDays(30),
            'end_date' => Carbon::now()->addDays(30),
            'status' => true,
            'description' => 'Áp dụng cho tất cả đơn hàng, giảm tối đa 500,000₫',
        ]);

        // Voucher giảm 20% - đơn hàng tối thiểu 5 triệu
        Voucher::create([
            'code' => 'GIAM20',
            'name' => 'Giảm 20% cho đơn hàng từ 5 triệu',
            'type' => 'percentage',
            'value' => 20,
            'min_order' => 5000000,
            'max_discount' => 2000000, // Giảm tối đa 2,000,000₫
            'usage_limit' => 100,
            'used_count' => 0,
            'start_date' => Carbon::now()->subDays(30),
            'end_date' => Carbon::now()->addDays(30),
            'status' => true,
            'description' => 'Áp dụng cho đơn hàng từ 5,000,000₫, giảm tối đa 2,000,000₫',
        ]);

        // Voucher giảm 500,000₫ - đơn hàng tối thiểu 3 triệu
        Voucher::create([
            'code' => 'GIAM500K',
            'name' => 'Giảm 500,000₫ cho đơn hàng từ 3 triệu',
            'type' => 'fixed',
            'value' => 500000,
            'min_order' => 3000000,
            'max_discount' => null,
            'usage_limit' => 50,
            'used_count' => 0,
            'start_date' => Carbon::now()->subDays(30),
            'end_date' => Carbon::now()->addDays(30),
            'status' => true,
            'description' => 'Áp dụng cho đơn hàng từ 3,000,000₫, giảm cố định 500,000₫',
        ]);

        // Voucher giảm 1,000,000₫ - đơn hàng tối thiểu 10 triệu
        Voucher::create([
            'code' => 'GIAM1M',
            'name' => 'Giảm 1,000,000₫ cho đơn hàng từ 10 triệu',
            'type' => 'fixed',
            'value' => 1000000,
            'min_order' => 10000000,
            'max_discount' => null,
            'usage_limit' => 20,
            'used_count' => 0,
            'start_date' => Carbon::now()->subDays(30),
            'end_date' => Carbon::now()->addDays(30),
            'status' => true,
            'description' => 'Áp dụng cho đơn hàng từ 10,000,000₫, giảm cố định 1,000,000₫',
        ]);

        // Voucher đã hết hạn (để test)
        Voucher::create([
            'code' => 'HETHAN',
            'name' => 'Voucher đã hết hạn',
            'type' => 'percentage',
            'value' => 15,
            'min_order' => 0,
            'max_discount' => null,
            'usage_limit' => null,
            'used_count' => 0,
            'start_date' => Carbon::now()->subDays(60),
            'end_date' => Carbon::now()->subDays(1),
            'status' => true,
            'description' => 'Voucher này đã hết hạn để test',
        ]);

        // Voucher đã hết lượt sử dụng
        Voucher::create([
            'code' => 'HETLUOT',
            'name' => 'Voucher đã hết lượt sử dụng',
            'type' => 'percentage',
            'value' => 10,
            'min_order' => 0,
            'max_discount' => null,
            'usage_limit' => 10,
            'used_count' => 10,
            'start_date' => Carbon::now()->subDays(30),
            'end_date' => Carbon::now()->addDays(30),
            'status' => true,
            'description' => 'Voucher này đã hết lượt sử dụng để test',
        ]);
    }
}
