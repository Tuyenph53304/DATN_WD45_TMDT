<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ShippingAddress;

class ShippingAddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo địa chỉ cho User ID 3 (Nguyen Van A - Người dùng)
        ShippingAddress::create([
            'user_id' => 3,
            'full_name' => 'Nguyen Van A',
            'phone' => '0987654321',
            'address' => '123 Đường ABC, Phường 1',
            'city' => 'Hà Nội',
            'default' => true,
        ]);

        ShippingAddress::create([
            'user_id' => 3,
            'full_name' => 'Nguyen Van A',
            'phone' => '0987654321',
            'address' => '456 Đường XYZ, Phường 2',
            'city' => 'Hồ Chí Minh',
            'default' => false,
        ]);

        // Tạo địa chỉ cho User ID 4 (Tran Thi B - Người dùng)
        ShippingAddress::create([
            'user_id' => 4,
            'full_name' => 'Tran Thi B',
            'phone' => '0987654322',
            'address' => '789 Đường DEF, Phường 3',
            'city' => 'Đà Nẵng',
            'default' => true,
        ]);

        // Tạo địa chỉ cho User ID 5 (Le Van C - Người dùng)
        ShippingAddress::create([
            'user_id' => 5,
            'full_name' => 'Le Van C',
            'phone' => '0987654323',
            'address' => '321 Đường GHI, Phường 4',
            'city' => 'Cần Thơ',
            'default' => true,
        ]);
    }
}
