<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        Category::create([
            'name' => 'Laptop Gaming',
            'slug' => 'laptop-gaming',
            'description' => 'Laptop chuyên dụng cho gaming với cấu hình mạnh mẽ',
            'status' => true,
        ]);

        Category::create([
            'name' => 'Laptop Văn phòng',
            'slug' => 'laptop-van-phong',
            'description' => 'Laptop phù hợp cho công việc văn phòng, mỏng nhẹ, pin lâu',
            'status' => true,
        ]);

        Category::create([
            'name' => 'Laptop Đồ họa',
            'slug' => 'laptop-do-hoa',
            'description' => 'Laptop chuyên dụng cho thiết kế đồ họa, render video',
            'status' => true,
        ]);

        Category::create([
            'name' => 'Phụ kiện',
            'slug' => 'phu-kien',
            'description' => 'Các phụ kiện laptop như chuột, bàn phím, túi đựng, v.v.',
            'status' => true,
        ]);

        Category::create([
            'name' => 'Màn hình',
            'slug' => 'man-hinh',
            'description' => 'Màn hình máy tính, màn hình gaming',
            'status' => true,
        ]);
    }
}
