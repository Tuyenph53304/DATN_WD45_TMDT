<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\AttributeValue;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Lấy ID của các giá trị thuộc tính đã tạo
        // (Trong dự án thật, bạn nên tìm ID linh động hơn)
        
        // RAM
        $ram8gb = AttributeValue::where('value', '8GB')->first()->id;
        $ram16gb = AttributeValue::where('value', '16GB')->first()->id;

        // CPU
        $cpuI5 = AttributeValue::where('value', 'Intel Core i5')->first()->id;
        $cpuI7 = AttributeValue::where('value', 'Intel Core i7')->first()->id;

        // Storage
        $storage512 = AttributeValue::where('value', '512GB SSD')->first()->id;

        // Color
        $colorBlack = AttributeValue::where('value', 'Đen')->first()->id;

        // Screen
        $screen15 = AttributeValue::where('value', '15.6 inch')->first()->id;

        // === TẠO SẢN PHẨM 1: BeeFast Pro X1 ===
        $product1 = Product::create([
            'category_id' => 2, // Laptop Văn phòng
            'name' => 'BeeFast Pro X1',
            'slug' => 'beefast-pro-x1',
            'description' => 'Mẫu laptop văn phòng mỏng nhẹ, hiệu năng cao.'
        ]);

        // Cấu hình 1: i5 / 8GB / 512GB / 15.6 inch / Đen
        $variant1 = ProductVariant::create([
            'product_id' => $product1->id,
            'sku' => 'BFPX1-I5-8-512',
            'price' => 20000000,
            'stock' => 50
        ]);
        // Gắn các thuộc tính cho cấu hình 1
        $variant1->attributeValues()->attach([$cpuI5, $ram8gb, $storage512, $colorBlack, $screen15]);

        // Cấu hình 2: i7 / 16GB / 512GB / 15.6 inch / Đen
        $variant2 = ProductVariant::create([
            'product_id' => $product1->id,
            'sku' => 'BFPX1-I7-16-512',
            'price' => 25000000,
            'stock' => 30
        ]);
        // Gắn các thuộc tính cho cấu hình 2
        $variant2->attributeValues()->attach([$cpuI7, $ram16gb, $storage512, $colorBlack, $screen15]);
    }
}