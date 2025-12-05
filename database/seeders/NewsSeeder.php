<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();

        News::create([
            'title' => 'Ra mắt bộ sưu tập mùa Hè 2024',
            'slug' => 'ra-mat-bo-suu-tap-mua-he-2024',
            'description' => 'Khám phá những thiết kế mới nhất trong bộ sưu tập mùa Hè của chúng tôi',
            'content' => '<p>Chúng tôi vui mừng giới thiệu bộ sưu tập mùa Hè 2024 với những thiết kế tươi mới, màu sắc rực rỡ và chất liệu thoáng mát. Mỗi sản phẩm được chọn lọc kỹ lưỡng để mang đến sự thoải mái tối đa cho khách hàng.</p><p>Với việc kết hợp giữa thời trang và công nghệ, các sản phẩm mới sẽ làm cho bạn nổi bật trong mùa hè này.</p>',
            'image_url' => 'https://images.unsplash.com/photo-1558769132-cb1aea458c5e?w=800&h=400&fit=crop',
            'author_id' => $admin?->id,
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(5),
            'views_count' => 245,
        ]);

        News::create([
            'title' => 'Các mẹo chăm sóc sản phẩm để kéo dài tuổi thọ',
            'slug' => 'cac-meo-cham-soc-san-pham-de-keo-dai-tuoi-tho',
            'description' => 'Hướng dẫn chi tiết cách bảo quản và chăm sóc các sản phẩm của bạn',
            'content' => '<p>Để đảm bảo sản phẩm của bạn luôn giữ được chất lượng và tuổi thọ, hãy thực hiện các bước chăm sóc sau:</p><ul><li>Vệ sinh sản phẩm định kỳ bằng nước ấm và xà phòng nhẹ</li><li>Tránh tiếp xúc với ánh nắng mặt trời trực tiếp trong thời gian dài</li><li>Bảo quản ở nơi thoáng mát, khô ráo</li><li>Sử dụng các dụng cụ chuyên dụng để vệ sinh</li></ul>',
            'image_url' => 'https://images.unsplash.com/photo-1552664730-d307ca884978?w=800&h=400&fit=crop',
            'author_id' => $admin?->id,
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(10),
            'views_count' => 189,
        ]);

        News::create([
            'title' => 'Tăng trưởng bền vững và trách nhiệm xã hội',
            'slug' => 'tang-trong-ben-vung-va-trach-nhiem-xa-hoi',
            'description' => 'Cam kết của chúng tôi đối với môi trường và cộng đồng',
            'content' => '<p>Chúng tôi tin rằng kinh doanh không chỉ là về lợi nhuận mà còn về trách nhiệm xã hội. Năm nay, chúng tôi đã thực hiện nhiều dự án:</p><ul><li>Sử dụng 100% vật liệu tái chế cho bao bì</li><li>Hỗ trợ các dự án giáo dục ở vùng sâu</li><li>Giảm khí thải carbon lên tới 30%</li><li>Hỗ trợ các doanh nghiệp vừa và nhỏ</li></ul>',
            'image_url' => 'https://images.unsplash.com/photo-1559027615-cd4628902d4a?w=800&h=400&fit=crop',
            'author_id' => $admin?->id,
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(15),
            'views_count' => 312,
        ]);

        News::create([
            'title' => 'Khuyến mãi đặc biệt cho khách hàng thân thiết',
            'slug' => 'khuyen-mai-dac-biet-cho-khach-hang-than-thiet',
            'description' => 'Chương trình ưu đãi dành riêng cho các thành viên VIP',
            'content' => '<p>Để cảm ơn sự ủng hộ của các khách hàng thân thiết, chúng tôi triển khai chương trình khuyến mãi đặc biệt:</p><ul><li>Giảm giá 20% cho tất cả sản phẩm</li><li>Miễn phí vận chuyển không giới hạn</li><li>Tích điểm gấp đôi cho mỗi lần mua</li><li>Quà tặng đặc biệt khi mua từ 3 sản phẩm trở lên</li></ul>',
            'image_url' => 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=800&h=400&fit=crop',
            'author_id' => $admin?->id,
            'is_published' => true,
            'published_at' => Carbon::now()->subDays(20),
            'views_count' => 478,
        ]);

        News::create([
            'title' => 'Chuẩn bị ra mắt ứng dụng di động mới',
            'slug' => 'chuan-bi-ra-mat-ung-dung-di-dong-moi',
            'description' => 'Ứng dụng mới với tính năng hiện đại và giao diện thân thiện',
            'content' => '<p>Chúng tôi sắp ra mắt phiên bản ứng dụng di động mới với nhiều cải tiến:</p><ul><li>Giao diện mới đẹp mắt và dễ sử dụng</li><li>Tính năng tìm kiếm thông minh với AI</li><li>Thanh toán nhanh chóng và an toàn</li><li>Theo dõi đơn hàng thực thời</li><li>Nhận thông báo khuyến mãi cá nhân hóa</li></ul>',
            'image_url' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=800&h=400&fit=crop',
            'author_id' => $admin?->id,
            'is_published' => false,
            'published_at' => null,
            'views_count' => 0,
        ]);
    }
}
