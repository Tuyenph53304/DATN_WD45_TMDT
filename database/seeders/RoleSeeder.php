<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // ID 1: Admin
        Role::create(['name' => 'Admin']);

        // ID 2: Khách
        Role::create(['name' => 'Khách']);

        // ID 3: Người dùng (default)
        Role::create(['name' => 'Người dùng']);
    }
}
