<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attribute;
use App\Models\AttributeValue;

class AttributeSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Tạo thuộc tính RAM
        $ram = Attribute::create(['name' => 'RAM', 'slug' => 'ram']);
        AttributeValue::create(['attribute_id' => $ram->id, 'value' => '8GB']);
        AttributeValue::create(['attribute_id' => $ram->id, 'value' => '16GB']);
        AttributeValue::create(['attribute_id' => $ram->id, 'value' => '32GB']);

        // 2. Tạo thuộc tính CPU
        $cpu = Attribute::create(['name' => 'CPU', 'slug' => 'cpu']);
        AttributeValue::create(['attribute_id' => $cpu->id, 'value' => 'Intel Core i5']);
        AttributeValue::create(['attribute_id' => $cpu->id, 'value' => 'Intel Core i7']);
        AttributeValue::create(['attribute_id' => $cpu->id, 'value' => 'Intel Core i9']);

        // 3. Tạo thuộc tính Ổ cứng
        $storage = Attribute::create(['name' => 'Ổ cứng', 'slug' => 'o-cung']);
        AttributeValue::create(['attribute_id' => $storage->id, 'value' => '256GB SSD']);
        AttributeValue::create(['attribute_id' => $storage->id, 'value' => '512GB SSD']);
        AttributeValue::create(['attribute_id' => $storage->id, 'value' => '1TB SSD']);

        // 4. Tạo thuộc tính Màu sắc
        $color = Attribute::create(['name' => 'Màu sắc', 'slug' => 'mau-sac']);
        AttributeValue::create(['attribute_id' => $color->id, 'value' => 'Đen']);
        AttributeValue::create(['attribute_id' => $color->id, 'value' => 'Bạc']);
        AttributeValue::create(['attribute_id' => $color->id, 'value' => 'Xám']);

        // 5. Tạo thuộc tính Kích cỡ màn hình
        $screen = Attribute::create(['name' => 'Kích cỡ màn hình', 'slug' => 'kich-co-man-hinh']);
        AttributeValue::create(['attribute_id' => $screen->id, 'value' => '13.3 inch']);
        AttributeValue::create(['attribute_id' => $screen->id, 'value' => '14 inch']);
        AttributeValue::create(['attribute_id' => $screen->id, 'value' => '15.6 inch']);
        AttributeValue::create(['attribute_id' => $screen->id, 'value' => '17.3 inch']);

        // 6. Tạo thuộc tính Card đồ họa
        $gpu = Attribute::create(['name' => 'Card đồ họa', 'slug' => 'card-do-hoa']);
        AttributeValue::create(['attribute_id' => $gpu->id, 'value' => 'Intel UHD Graphics']);
        AttributeValue::create(['attribute_id' => $gpu->id, 'value' => 'NVIDIA GeForce RTX 3050']);
        AttributeValue::create(['attribute_id' => $gpu->id, 'value' => 'NVIDIA GeForce RTX 3060']);
        AttributeValue::create(['attribute_id' => $gpu->id, 'value' => 'NVIDIA GeForce RTX 4070']);
        AttributeValue::create(['attribute_id' => $gpu->id, 'value' => 'AMD Radeon RX 6600M']);
    }
}
