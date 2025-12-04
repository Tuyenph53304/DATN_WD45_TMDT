<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\AttributeValue;

class ProductSeeder extends Seeder
{
    /**
     * Helper method để lấy ID của AttributeValue
     */
    private function getAttributeValueId(string $value): int
    {
        $attrValue = AttributeValue::where('value', $value)->first();
        if (!$attrValue) {
            throw new \Exception("AttributeValue với value '{$value}' không tồn tại. Vui lòng chạy AttributeSeeder trước.");
        }
        return $attrValue->id;
    }

    public function run(): void
    {
        // Lấy ID của các giá trị thuộc tính đã tạo

        // RAM
        $ram8gb = $this->getAttributeValueId('8GB');
        $ram16gb = $this->getAttributeValueId('16GB');

        // CPU
        $cpuI5 = $this->getAttributeValueId('Intel Core i5');
        $cpuI7 = $this->getAttributeValueId('Intel Core i7');

        // Storage
        $storage512 = $this->getAttributeValueId('512GB SSD');

        // Color
        $colorBlack = $this->getAttributeValueId('Đen');

        // Screen
        $screen15 = $this->getAttributeValueId('15.6 inch');

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

        // === TẠO SẢN PHẨM 2: BeeFast Gaming Z1 ===
        $product2 = Product::create([
            'category_id' => 1, // Laptop Gaming
            'name' => 'BeeFast Gaming Z1',
            'slug' => 'beefast-gaming-z1',
            'description' => 'Laptop gaming hiệu năng cao, màn hình 144Hz, card đồ họa RTX 3060.'
        ]);

        // Lấy thêm các giá trị thuộc tính cần thiết
        $ram32gb = $this->getAttributeValueId('32GB');
        $cpuI9 = $this->getAttributeValueId('Intel Core i9');
        $storage1tb = $this->getAttributeValueId('1TB SSD');
        $colorSilver = $this->getAttributeValueId('Bạc');
        $screen17 = $this->getAttributeValueId('17.3 inch');
        $gpuRTX3060 = $this->getAttributeValueId('NVIDIA GeForce RTX 3060');
        $gpuRTX4070 = $this->getAttributeValueId('NVIDIA GeForce RTX 4070');

        // Cấu hình 1: i7 / 16GB / 512GB / 15.6 inch / Đen / RTX 3060
        $variant3 = ProductVariant::create([
            'product_id' => $product2->id,
            'sku' => 'BFGZ1-I7-16-512-RTX3060',
            'price' => 35000000,
            'stock' => 25
        ]);
        $variant3->attributeValues()->attach([$cpuI7, $ram16gb, $storage512, $colorBlack, $screen15, $gpuRTX3060]);

        // Cấu hình 2: i9 / 32GB / 1TB / 17.3 inch / Bạc / RTX 4070
        $variant4 = ProductVariant::create([
            'product_id' => $product2->id,
            'sku' => 'BFGZ1-I9-32-1TB-RTX4070',
            'price' => 55000000,
            'stock' => 15
        ]);
        $variant4->attributeValues()->attach([$cpuI9, $ram32gb, $storage1tb, $colorSilver, $screen17, $gpuRTX4070]);

        // === TẠO SẢN PHẨM 3: BeeFast Ultra S1 ===
        $product3 = Product::create([
            'category_id' => 2, // Laptop Văn phòng
            'name' => 'BeeFast Ultra S1',
            'slug' => 'beefast-ultra-s1',
            'description' => 'Laptop siêu mỏng nhẹ, pin 12 giờ, màn hình 14 inch Full HD.'
        ]);

        $screen14 = $this->getAttributeValueId('14 inch');
        $screen133 = $this->getAttributeValueId('13.3 inch');
        $storage256 = $this->getAttributeValueId('256GB SSD');
        $colorGray = $this->getAttributeValueId('Xám');

        // Cấu hình 1: i5 / 8GB / 256GB / 13.3 inch / Xám
        $variant5 = ProductVariant::create([
            'product_id' => $product3->id,
            'sku' => 'BFUS1-I5-8-256',
            'price' => 18000000,
            'stock' => 40
        ]);
        $variant5->attributeValues()->attach([$cpuI5, $ram8gb, $storage256, $colorGray, $screen133]);

        // Cấu hình 2: i5 / 16GB / 512GB / 14 inch / Đen
        $variant6 = ProductVariant::create([
            'product_id' => $product3->id,
            'sku' => 'BFUS1-I5-16-512',
            'price' => 22000000,
            'stock' => 35
        ]);
        $variant6->attributeValues()->attach([$cpuI5, $ram16gb, $storage512, $colorBlack, $screen14]);

        // === TẠO SẢN PHẨM 4: BeeFast Design D1 ===
        $product4 = Product::create([
            'category_id' => 3, // Laptop Đồ họa
            'name' => 'BeeFast Design D1',
            'slug' => 'beefast-design-d1',
            'description' => 'Laptop chuyên dụng cho thiết kế đồ họa, màn hình 4K, màu sắc chính xác.'
        ]);

        $gpuRTX3050 = $this->getAttributeValueId('NVIDIA GeForce RTX 3050');

        // Cấu hình 1: i7 / 16GB / 512GB / 15.6 inch / Đen / RTX 3050
        $variant7 = ProductVariant::create([
            'product_id' => $product4->id,
            'sku' => 'BFDD1-I7-16-512-RTX3050',
            'price' => 32000000,
            'stock' => 20
        ]);
        $variant7->attributeValues()->attach([$cpuI7, $ram16gb, $storage512, $colorBlack, $screen15, $gpuRTX3050]);

        // Cấu hình 2: i9 / 32GB / 1TB / 17.3 inch / Bạc / RTX 4070
        $variant8 = ProductVariant::create([
            'product_id' => $product4->id,
            'sku' => 'BFDD1-I9-32-1TB-RTX4070',
            'price' => 58000000,
            'stock' => 10
        ]);
        $variant8->attributeValues()->attach([$cpuI9, $ram32gb, $storage1tb, $colorSilver, $screen17, $gpuRTX4070]);
    }
}
