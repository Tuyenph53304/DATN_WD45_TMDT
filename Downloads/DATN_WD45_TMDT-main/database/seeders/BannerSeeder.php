<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Banner::create([
            'title' => 'Mua sắm hàng Tết - Giảm giá lên đến 50%',
            'description' => 'Khuyến mãi đặc biệt cho mùa Tết năm nay',
            'image_url' => 'https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1200&h=400&fit=crop',
            'link' => 'https://example.com/tet-sale',
            'is_active' => true,
            'order' => 1,
        ]);

        Banner::create([
            'title' => 'Flash Sale Hàng Mới 24h - Giảm 40%',
            'description' => 'Cơ hội vàng mua sắm hàng mới với giá cực rẻ',
            'image_url' => 'https://images.unsplash.com/photo-1556821552-5f54f1e8d88d?w=1200&h=400&fit=crop',
            'link' => 'https://example.com/flash-sale',
            'is_active' => true,
            'order' => 2,
        ]);

        Banner::create([
            'title' => 'Thẻ Thành Viên VIP - Nhận lợi ích độc quyền',
            'description' => 'Đăng ký thẻ VIP để nhận ưu đãi tuyệt vời',
            'image_url' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=1200&h=400&fit=crop',
            'link' => 'https://example.com/vip-membership',
            'is_active' => true,
            'order' => 3,
        ]);

        Banner::create([
            'title' => 'Miễn phí vận chuyển cho đơn hàng trên 500k',
            'description' => 'Không phụ phí giao hàng khi mua trên 500,000 VNĐ',
            'image_url' => 'https://images.unsplash.com/photo-1578926078328-123456789abc?w=1200&h=400&fit=crop',
            'link' => 'https://example.com/free-shipping',
            'is_active' => true,
            'order' => 4,
        ]);
    }
}
