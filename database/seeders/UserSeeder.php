<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Tạo Admin
        User::create([
            'name' => 'BeeFast Admin',
            'email' => 'admin@beefast.com',
            'password' => Hash::make('password123'), // Mật khẩu là 'password123'
            'phone_number' => '0123456789',
            'role' => 'admin',
            'status' => true,
        ]);

        // Tạo tài khoản Khách - không đăng ký, chỉ xem
        User::create([
            'name' => 'Khách Vãng Lai',
            'email' => 'guest@beefast.com',
            'password' => Hash::make('password123'),
            'phone_number' => '0000000000',
            'role' => 'khách',
            'status' => true,
        ]);

        // Tạo nhiều Người dùng để test
        User::create([
            'name' => 'Nguyen Van A',
            'email' => 'usera@beefast.com',
            'password' => Hash::make('password123'),
            'phone_number' => '0987654321',
            'address' => '123 Đường ABC, Quận 1',
            'role' => 'user', // Default là 'user'
            'status' => true,
        ]);

        User::create([
            'name' => 'Tran Thi B',
            'email' => 'userb@beefast.com',
            'password' => Hash::make('password123'),
            'phone_number' => '0987654322',
            'address' => '456 Đường XYZ, Quận 2',
            'role' => 'user',
            'status' => true,
        ]);

        User::create([
            'name' => 'Le Van C',
            'email' => 'userc@beefast.com',
            'password' => Hash::make('password123'),
            'phone_number' => '0987654323',
            'address' => '789 Đường DEF, Quận 3',
            'role' => 'user',
            'status' => true,
        ]);

        User::create([
            'name' => 'Pham Thi D',
            'email' => 'userd@beefast.com',
            'password' => Hash::make('password123'),
            'phone_number' => '0987654324',
            'address' => '321 Đường GHI, Quận 4',
            'role' => 'user',
            'status' => false, // Tài khoản bị khóa để test
        ]);
    }
}
