<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            SettingsSeeder::class,
            PageSeeder::class,
            DemoCustomerSeeder::class,
            JewelryStoreSeeder::class,
            ClothingStoreSeeder::class,
            HomeSeeder::class,
            TestimonialSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}
