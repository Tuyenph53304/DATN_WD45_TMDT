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
            'slug' => 'laptop-gaming'
        ]);

        Category::create([
            'name' => 'Laptop Văn phòng',
            'slug' => 'laptop-van-phong'
        ]);

        Category::create([
            'name' => 'Phụ kiện',
            'slug' => 'phu-kien'
        ]);
    }
}
