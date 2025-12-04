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
        // Tạo một địa chỉ cho User ID 2 (Nguyen Van A)
        ShippingAddress::create([
            'user_id' => 2,
            'full_name' => 'Nguyen Van A',
            'phone' => '0987654321',
            'address' => '123 Đường ABC',
            'city' => 'Hà Nội',
            'default' => true,
        ]);
    }
}
