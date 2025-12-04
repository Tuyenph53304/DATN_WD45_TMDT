<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            AttributeSeeder::class,
            ProductSeeder::class, // ProductSeeder phải chạy sau AttributeSeeder
            ShippingAddressSeeder::class, // ShippingAddressSeeder phải chạy sau UserSeeder
            VoucherSeeder::class,
        ]);
    }
}
