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
            'role_id' => 1, // ID 1 là Admin (theo RoleSeeder)
            'status' => true,
        ]);

        // Tạo Customer
        User::create([
            'name' => 'Nguyen Van A',
            'email' => 'customera@beefast.com',
            'password' => Hash::make('password123'),
            'phone_number' => '0987654321',
            'role_id' => 2, // ID 2 là Customer
            'status' => true,
        ]);
    }
}
