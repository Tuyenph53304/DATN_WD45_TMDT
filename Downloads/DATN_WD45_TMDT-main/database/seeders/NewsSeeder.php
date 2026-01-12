<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::create([
            'title' => 'Chào mừng năm mới 2025 - Ưu đãi đặc biệt',
            'slug' => 'chao-mung-nam-moi-2025-uu-dai-dac-biet',
            'content' => 'Năm mới 2025 đã đến, mang theo những cơ hội mua sắm tuyệt vời với chương trình ưu đãi đặc biệt từ cửa hàng chúng tôi. Giảm giá lên đến 50% cho tất cả sản phẩm, miễn phí vận chuyển cho đơn hàng trên 500k.',
            'excerpt' => 'Năm mới 2025 với ưu đãi đặc biệt - giảm giá 50%, miễn phí vận chuyển.',
            'image' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=800&h=400&fit=crop',
            'status' => true,
            'views' => 0,
        ]);

        News::create([
            'title' => 'Hướng dẫn mua hàng online an toàn',
            'slug' => 'huong-dan-mua-hang-online-an-toan',
            'content' => 'Mua hàng online ngày càng phổ biến, nhưng để đảm bảo an toàn, bạn cần lưu ý một số điểm quan trọng. Hãy chọn website uy tín, kiểm tra chính sách bảo mật, và sử dụng phương thức thanh toán an toàn.',
            'excerpt' => 'Những lưu ý quan trọng khi mua hàng online để đảm bảo an toàn và bảo vệ quyền lợi.',
            'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&h=400&fit=crop',
            'status' => true,
            'views' => 0,
        ]);

        News::create([
            'title' => 'Ra mắt bộ sưu tập mùa xuân 2025',
            'slug' => 'ra-mat-bo-suu-tap-mua-xuan-2025',
            'content' => 'Bộ sưu tập mùa xuân 2025 với những thiết kế mới mẻ, màu sắc tươi sáng và chất liệu cao cấp. Khám phá ngay những mẫu mã hot nhất trong năm nay.',
            'excerpt' => 'Bộ sưu tập mùa xuân 2025 - thiết kế mới, màu sắc tươi sáng.',
            'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=800&h=400&fit=crop',
            'status' => true,
            'views' => 0,
        ]);

        News::create([
            'title' => 'Chính sách đổi trả hàng hóa',
            'slug' => 'chinh-sach-doi-tra-hang-hoa',
            'content' => 'Chúng tôi cam kết mang đến trải nghiệm mua sắm tốt nhất cho khách hàng. Chính sách đổi trả linh hoạt trong vòng 30 ngày, áp dụng cho tất cả sản phẩm còn nguyên tem mác.',
            'excerpt' => 'Chính sách đổi trả hàng hóa linh hoạt trong 30 ngày.',
            'image' => 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=800&h=400&fit=crop',
            'status' => true,
            'views' => 0,
        ]);
    }
}
