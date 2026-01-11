<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\AttributeValue;
use Illuminate\Support\Str;

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
        // Xóa dữ liệu cũ nếu có (để tránh trùng lặp)
        // Xóa variants trước vì có foreign key đến products
        ProductVariant::query()->delete();
        // Sau đó xóa products
        Product::query()->delete();

        // Lấy tất cả ID của các giá trị thuộc tính
        $ram8gb = $this->getAttributeValueId('8GB');
        $ram16gb = $this->getAttributeValueId('16GB');
        $ram32gb = $this->getAttributeValueId('32GB');

        $cpuI5 = $this->getAttributeValueId('Intel Core i5');
        $cpuI7 = $this->getAttributeValueId('Intel Core i7');
        $cpuI9 = $this->getAttributeValueId('Intel Core i9');

        $storage256 = $this->getAttributeValueId('256GB SSD');
        $storage512 = $this->getAttributeValueId('512GB SSD');
        $storage1tb = $this->getAttributeValueId('1TB SSD');

        $colorBlack = $this->getAttributeValueId('Đen');
        $colorSilver = $this->getAttributeValueId('Bạc');
        $colorGray = $this->getAttributeValueId('Xám');

        $screen133 = $this->getAttributeValueId('13.3 inch');
        $screen14 = $this->getAttributeValueId('14 inch');
        $screen15 = $this->getAttributeValueId('15.6 inch');
        $screen17 = $this->getAttributeValueId('17.3 inch');
        
        $gpuIntel = $this->getAttributeValueId('Intel UHD Graphics');
        $gpuRTX3050 = $this->getAttributeValueId('NVIDIA GeForce RTX 3050');
        $gpuRTX3060 = $this->getAttributeValueId('NVIDIA GeForce RTX 3060');
        $gpuRTX4070 = $this->getAttributeValueId('NVIDIA GeForce RTX 4070');
        $gpuAMD = $this->getAttributeValueId('AMD Radeon RX 6600M');

        // Danh sách 80 sản phẩm với đầy đủ thông tin
        $products = [
            // Laptop Gaming (Category 1) - 20 sản phẩm
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z1', 'description' => 'Laptop gaming hiệu năng cao, màn hình 144Hz, card đồ họa RTX 3060. Thiết kế đẹp mắt với đèn LED RGB, tản nhiệt hiệu quả.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z2 Pro', 'description' => 'Laptop gaming cao cấp với RTX 4070, màn hình 17.3 inch 165Hz, bàn phím cơ RGB. Hiệu năng vượt trội cho mọi tựa game AAA.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z3 Ultra', 'description' => 'Laptop gaming flagship với Intel Core i9, 32GB RAM, RTX 4070. Màn hình 2K 240Hz, tản nhiệt liquid cooling.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z4', 'description' => 'Laptop gaming tầm trung với RTX 3060, màn hình 15.6 inch 144Hz. Phù hợp cho game thủ với ngân sách hợp lý.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z5 Max', 'description' => 'Laptop gaming màn hình lớn 17.3 inch, RTX 4070, pin lâu. Thiết kế mạnh mẽ, tản nhiệt tốt cho gaming marathon.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z6', 'description' => 'Laptop gaming nhỏ gọn 15.6 inch, RTX 3050, hiệu năng ổn định. Phù hợp cho game thủ di động.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z7 Pro', 'description' => 'Laptop gaming với AMD Radeon RX 6600M, màn hình 144Hz. Hiệu năng tốt, giá cả hợp lý.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z8', 'description' => 'Laptop gaming entry-level với RTX 3050, màn hình 15.6 inch. Phù hợp cho người mới bắt đầu chơi game.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z9 Elite', 'description' => 'Laptop gaming cao cấp với RTX 4070, Intel i9, 32GB RAM. Màn hình 2K 165Hz, thiết kế premium.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z10', 'description' => 'Laptop gaming tầm trung với RTX 3060, màn hình 15.6 inch. Cân bằng giữa hiệu năng và giá cả.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z11 Max', 'description' => 'Laptop gaming màn hình lớn 17.3 inch, RTX 4070, pin 8 giờ. Phù hợp cho game thủ chuyên nghiệp.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z12', 'description' => 'Laptop gaming compact với RTX 3050, màn hình 15.6 inch. Thiết kế gọn nhẹ, dễ mang theo.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z13 Pro', 'description' => 'Laptop gaming với RTX 3060, Intel i7, 16GB RAM. Màn hình 144Hz, tản nhiệt hiệu quả.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z14', 'description' => 'Laptop gaming entry với RTX 3050, màn hình 15.6 inch. Giá cả phải chăng cho game thủ mới.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z15 Ultra', 'description' => 'Laptop gaming flagship với RTX 4070, Intel i9, 32GB RAM. Màn hình 2K 240Hz, hiệu năng tối đa.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z16', 'description' => 'Laptop gaming với AMD RX 6600M, màn hình 144Hz. Hiệu năng tốt, giá hợp lý.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z17', 'description' => 'Laptop gaming tầm trung với RTX 3060, màn hình 15.6 inch. Cân bằng hiệu năng và giá cả.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z18 Pro', 'description' => 'Laptop gaming cao cấp với RTX 4070, màn hình 17.3 inch. Thiết kế đẹp, hiệu năng mạnh.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z19', 'description' => 'Laptop gaming với RTX 3050, Intel i5, 8GB RAM. Phù hợp cho game nhẹ và vừa.', 'status' => true],
            ['category_id' => 1, 'name' => 'BeeFast Gaming Z20 Elite', 'description' => 'Laptop gaming flagship với RTX 4070, Intel i9, 32GB RAM. Màn hình 2K 165Hz, thiết kế premium.', 'status' => true],

            // Laptop Văn phòng (Category 2) - 20 sản phẩm
            ['category_id' => 2, 'name' => 'BeeFast Pro X1', 'description' => 'Mẫu laptop văn phòng mỏng nhẹ, hiệu năng cao. Pin lâu 10 giờ, màn hình Full HD sắc nét.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Ultra S1', 'description' => 'Laptop siêu mỏng nhẹ, pin 12 giờ, màn hình 14 inch Full HD. Thiết kế sang trọng, phù hợp công việc văn phòng.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Pro X2', 'description' => 'Laptop văn phòng với Intel i5, 8GB RAM, 512GB SSD. Màn hình 15.6 inch, pin lâu, giá hợp lý.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Ultra S2', 'description' => 'Laptop siêu mỏng 13.3 inch, Intel i5, 8GB RAM. Thiết kế gọn nhẹ, pin 12 giờ, phù hợp di động.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Pro X3', 'description' => 'Laptop văn phòng với Intel i7, 16GB RAM, 512GB SSD. Màn hình 15.6 inch Full HD, hiệu năng mạnh.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Ultra S3', 'description' => 'Laptop siêu mỏng 14 inch, Intel i5, 16GB RAM. Thiết kế đẹp, pin lâu, phù hợp công việc.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Pro X4', 'description' => 'Laptop văn phòng entry-level với Intel i5, 8GB RAM. Màn hình 15.6 inch, giá cả phải chăng.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Ultra S4', 'description' => 'Laptop siêu mỏng 13.3 inch, Intel i5, 8GB RAM. Thiết kế gọn nhẹ, pin 10 giờ.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Pro X5', 'description' => 'Laptop văn phòng với Intel i7, 16GB RAM, 1TB SSD. Màn hình 15.6 inch, hiệu năng cao.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Ultra S5', 'description' => 'Laptop siêu mỏng 14 inch, Intel i7, 16GB RAM. Thiết kế sang trọng, pin 12 giờ.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Pro X6', 'description' => 'Laptop văn phòng với Intel i5, 8GB RAM, 256GB SSD. Màn hình 15.6 inch, giá hợp lý.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Ultra S6', 'description' => 'Laptop siêu mỏng 13.3 inch, Intel i5, 8GB RAM. Thiết kế compact, pin lâu.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Pro X7', 'description' => 'Laptop văn phòng với Intel i7, 16GB RAM, 512GB SSD. Màn hình 15.6 inch Full HD, hiệu năng tốt.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Ultra S7', 'description' => 'Laptop siêu mỏng 14 inch, Intel i5, 16GB RAM. Thiết kế đẹp, pin 11 giờ.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Pro X8', 'description' => 'Laptop văn phòng entry với Intel i5, 8GB RAM. Màn hình 15.6 inch, giá cả phải chăng.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Ultra S8', 'description' => 'Laptop siêu mỏng 13.3 inch, Intel i5, 8GB RAM. Thiết kế gọn nhẹ, pin 10 giờ.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Pro X9', 'description' => 'Laptop văn phòng với Intel i7, 16GB RAM, 1TB SSD. Màn hình 15.6 inch, hiệu năng mạnh.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Ultra S9', 'description' => 'Laptop siêu mỏng 14 inch, Intel i7, 16GB RAM. Thiết kế sang trọng, pin 12 giờ.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Pro X10', 'description' => 'Laptop văn phòng với Intel i5, 8GB RAM, 512GB SSD. Màn hình 15.6 inch, giá hợp lý.', 'status' => true],
            ['category_id' => 2, 'name' => 'BeeFast Ultra S10', 'description' => 'Laptop siêu mỏng 13.3 inch, Intel i5, 8GB RAM. Thiết kế compact, pin lâu, phù hợp di động.', 'status' => true],

            // Laptop Đồ họa (Category 3) - 20 sản phẩm
            ['category_id' => 3, 'name' => 'BeeFast Design D1', 'description' => 'Laptop chuyên dụng cho thiết kế đồ họa, màn hình 4K, màu sắc chính xác. RTX 3050, Intel i7, 16GB RAM.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D2 Pro', 'description' => 'Laptop đồ họa cao cấp với RTX 4070, Intel i9, 32GB RAM. Màn hình 17.3 inch 4K, màu sắc chính xác 100% Adobe RGB.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D3', 'description' => 'Laptop đồ họa với RTX 3060, Intel i7, 16GB RAM. Màn hình 15.6 inch 2K, phù hợp thiết kế đồ họa.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D4 Ultra', 'description' => 'Laptop đồ họa flagship với RTX 4070, Intel i9, 32GB RAM. Màn hình 17.3 inch 4K OLED, màu sắc tuyệt đối.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D5', 'description' => 'Laptop đồ họa với RTX 3050, Intel i7, 16GB RAM. Màn hình 15.6 inch Full HD, giá hợp lý.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D6 Pro', 'description' => 'Laptop đồ họa với RTX 4070, Intel i9, 32GB RAM. Màn hình 17.3 inch 4K, hiệu năng render mạnh.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D7', 'description' => 'Laptop đồ họa với RTX 3060, Intel i7, 16GB RAM. Màn hình 15.6 inch 2K, phù hợp thiết kế.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D8', 'description' => 'Laptop đồ họa entry với RTX 3050, Intel i5, 8GB RAM. Màn hình 15.6 inch, giá cả phải chăng.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D9 Elite', 'description' => 'Laptop đồ họa cao cấp với RTX 4070, Intel i9, 32GB RAM. Màn hình 17.3 inch 4K, thiết kế premium.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D10', 'description' => 'Laptop đồ họa với RTX 3060, Intel i7, 16GB RAM. Màn hình 15.6 inch 2K, hiệu năng tốt.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D11', 'description' => 'Laptop đồ họa với RTX 3050, Intel i7, 16GB RAM. Màn hình 15.6 inch Full HD, phù hợp thiết kế cơ bản.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D12 Pro', 'description' => 'Laptop đồ họa với RTX 4070, Intel i9, 32GB RAM. Màn hình 17.3 inch 4K, render video nhanh.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D13', 'description' => 'Laptop đồ họa với RTX 3060, Intel i7, 16GB RAM. Màn hình 15.6 inch 2K, cân bằng hiệu năng và giá.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D14', 'description' => 'Laptop đồ họa entry với RTX 3050, Intel i5, 8GB RAM. Màn hình 15.6 inch, giá cả phải chăng.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D15 Ultra', 'description' => 'Laptop đồ họa flagship với RTX 4070, Intel i9, 32GB RAM. Màn hình 17.3 inch 4K OLED, hiệu năng tối đa.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D16', 'description' => 'Laptop đồ họa với RTX 3060, Intel i7, 16GB RAM. Màn hình 15.6 inch 2K, phù hợp thiết kế chuyên nghiệp.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D17', 'description' => 'Laptop đồ họa với RTX 3050, Intel i7, 16GB RAM. Màn hình 15.6 inch Full HD, giá hợp lý.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D18 Pro', 'description' => 'Laptop đồ họa với RTX 4070, Intel i9, 32GB RAM. Màn hình 17.3 inch 4K, thiết kế đẹp.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D19', 'description' => 'Laptop đồ họa với RTX 3060, Intel i7, 16GB RAM. Màn hình 15.6 inch 2K, hiệu năng ổn định.', 'status' => true],
            ['category_id' => 3, 'name' => 'BeeFast Design D20 Elite', 'description' => 'Laptop đồ họa cao cấp với RTX 4070, Intel i9, 32GB RAM. Màn hình 17.3 inch 4K, màu sắc chính xác tuyệt đối.', 'status' => true],

            // Phụ kiện (Category 4) - 10 sản phẩm
            ['category_id' => 4, 'name' => 'Chuột không dây BeeFast M1', 'description' => 'Chuột không dây công nghệ Bluetooth 5.0, độ phân giải 1600 DPI. Pin sử dụng 6 tháng, thiết kế ergonomic.', 'status' => true],
            ['category_id' => 4, 'name' => 'Bàn phím cơ BeeFast K1', 'description' => 'Bàn phím cơ switch Blue, đèn LED RGB, thiết kế full-size. Phù hợp cho gaming và làm việc.', 'status' => true],
            ['category_id' => 4, 'name' => 'Túi đựng laptop BeeFast B1', 'description' => 'Túi đựng laptop chống sốc, chống nước, thiết kế sang trọng. Phù hợp laptop 15.6 inch.', 'status' => true],
            ['category_id' => 4, 'name' => 'Chuột gaming BeeFast M2 Pro', 'description' => 'Chuột gaming độ phân giải 12000 DPI, đèn LED RGB, 8 nút có thể lập trình. Thiết kế ergonomic cho game thủ.', 'status' => true],
            ['category_id' => 4, 'name' => 'Bàn phím không dây BeeFast K2', 'description' => 'Bàn phím không dây Bluetooth, pin sử dụng 3 tháng, thiết kế compact. Phù hợp văn phòng và di động.', 'status' => true],
            ['category_id' => 4, 'name' => 'Túi balo laptop BeeFast B2', 'description' => 'Balo laptop chống sốc, nhiều ngăn, thiết kế hiện đại. Phù hợp laptop 17.3 inch và phụ kiện.', 'status' => true],
            ['category_id' => 4, 'name' => 'Chuột không dây BeeFast M3', 'description' => 'Chuột không dây công nghệ 2.4GHz, độ phân giải 2400 DPI. Pin sử dụng 12 tháng, thiết kế gọn nhẹ.', 'status' => true],
            ['category_id' => 4, 'name' => 'Bàn phím cơ BeeFast K3 RGB', 'description' => 'Bàn phím cơ switch Red, đèn LED RGB per-key, thiết kế TKL. Phù hợp cho gaming chuyên nghiệp.', 'status' => true],
            ['category_id' => 4, 'name' => 'Túi đựng laptop BeeFast B3', 'description' => 'Túi đựng laptop chống sốc, chống nước IPX4, thiết kế slim. Phù hợp laptop 13-14 inch.', 'status' => true],
            ['category_id' => 4, 'name' => 'Bộ chuột bàn phím BeeFast Combo', 'description' => 'Bộ combo chuột và bàn phím không dây, công nghệ 2.4GHz. Pin sử dụng lâu, thiết kế đồng bộ đẹp mắt.', 'status' => true],

            // Màn hình (Category 5) - 10 sản phẩm
            ['category_id' => 5, 'name' => 'Màn hình BeeFast Monitor M1 24 inch', 'description' => 'Màn hình Full HD 1920x1080, 75Hz, IPS panel. Màu sắc chính xác, góc nhìn rộng 178 độ.', 'status' => true],
            ['category_id' => 5, 'name' => 'Màn hình BeeFast Monitor M2 27 inch', 'description' => 'Màn hình 2K QHD 2560x1440, 144Hz, IPS panel. Phù hợp gaming và làm việc, màu sắc tuyệt vời.', 'status' => true],
            ['category_id' => 5, 'name' => 'Màn hình BeeFast Monitor M3 32 inch', 'description' => 'Màn hình 4K UHD 3840x2160, 60Hz, IPS panel. Màu sắc chính xác 99% sRGB, phù hợp đồ họa.', 'status' => true],
            ['category_id' => 5, 'name' => 'Màn hình gaming BeeFast Monitor M4 27 inch', 'description' => 'Màn hình gaming 2K 2560x1440, 165Hz, VA panel. Thời gian phản hồi 1ms, FreeSync Premium.', 'status' => true],
            ['category_id' => 5, 'name' => 'Màn hình BeeFast Monitor M5 24 inch', 'description' => 'Màn hình Full HD 1920x1080, 60Hz, IPS panel. Giá cả phải chăng, phù hợp văn phòng.', 'status' => true],
            ['category_id' => 5, 'name' => 'Màn hình gaming BeeFast Monitor M6 27 inch', 'description' => 'Màn hình gaming 2K 2560x1440, 240Hz, IPS panel. Thời gian phản hồi 0.5ms, G-Sync Compatible.', 'status' => true],
            ['category_id' => 5, 'name' => 'Màn hình BeeFast Monitor M7 32 inch', 'description' => 'Màn hình 4K UHD 3840x2160, 144Hz, IPS panel. Màu sắc chính xác, phù hợp gaming và đồ họa.', 'status' => true],
            ['category_id' => 5, 'name' => 'Màn hình BeeFast Monitor M8 24 inch', 'description' => 'Màn hình Full HD 1920x1080, 75Hz, VA panel. Độ tương phản cao, giá hợp lý.', 'status' => true],
            ['category_id' => 5, 'name' => 'Màn hình gaming BeeFast Monitor M9 27 inch', 'description' => 'Màn hình gaming 2K 2560x1440, 180Hz, IPS panel. Thời gian phản hồi 1ms, HDR 400.', 'status' => true],
            ['category_id' => 5, 'name' => 'Màn hình BeeFast Monitor M10 32 inch', 'description' => 'Màn hình 4K UHD 3840x2160, 60Hz, IPS panel. Màu sắc chính xác 100% Adobe RGB, phù hợp chuyên nghiệp.', 'status' => true],
        ];

        // Tạo 80 sản phẩm
        foreach ($products as $index => $productData) {
            // Tạo slug unique
            $baseSlug = Str::slug($productData['name']);
            $slug = $baseSlug . '-' . ($index + 1);
            
            // Đảm bảo slug unique (nếu trùng thì thêm số ngẫu nhiên)
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . ($index + 1) . '-' . $counter;
                $counter++;
            }
            
            $product = Product::create([
                'category_id' => $productData['category_id'],
                'name' => $productData['name'],
                'slug' => $slug,
                'description' => $productData['description'],
                'status' => $productData['status'],
            ]);

            // Tạo variants cho từng sản phẩm dựa trên category
            $productCode = strtoupper(preg_replace('/[^A-Z0-9]/', '', substr($productData['name'], 8, 5)));
            if (empty($productCode)) {
                $productCode = 'P' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            }
            
            if ($productData['category_id'] <= 3) { // Laptop (Gaming, Văn phòng, Đồ họa)
                // Variant 1: Cấu hình cơ bản
                $variant1 = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => 'BF-' . $productCode . '-V1-' . ($index + 1),
                    'price' => rand(15000000, 25000000),
                    'stock' => rand(20, 100),
                ]);
                
                // Gắn thuộc tính cho variant 1
                $variant1Attrs = [$cpuI5, $ram8gb, $storage512, $colorBlack, $screen15];
                if ($productData['category_id'] == 1 || $productData['category_id'] == 3) {
                    $variant1Attrs[] = $gpuRTX3050;
                } else {
                    $variant1Attrs[] = $gpuIntel;
                }
                $variant1->attributeValues()->attach($variant1Attrs);

                // Variant 2: Cấu hình cao cấp
                $variant2 = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => 'BF-' . $productCode . '-V2-' . ($index + 1),
                    'price' => rand(25000000, 60000000),
                    'stock' => rand(10, 50),
                ]);
                
                // Gắn thuộc tính cho variant 2
                $variant2Attrs = [$cpuI7, $ram16gb, $storage1tb, $colorSilver, $screen17];
                if ($productData['category_id'] == 1 || $productData['category_id'] == 3) {
                    $variant2Attrs[] = $gpuRTX4070;
                } else {
                    $variant2Attrs[] = $gpuIntel;
                }
                $variant2->attributeValues()->attach($variant2Attrs);
            } else {
                // Phụ kiện và Màn hình - chỉ tạo 1 variant
                $variant = ProductVariant::create([
                    'product_id' => $product->id,
                    'sku' => 'BF-' . $productCode . '-' . ($index + 1),
                    'price' => $productData['category_id'] == 4 ? rand(200000, 2000000) : rand(3000000, 15000000),
                    'stock' => rand(50, 200),
                ]);
            }
        }
    }
}
