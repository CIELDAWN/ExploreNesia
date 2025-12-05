<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProvinceAndCitySeeder::class,
            CategorySeeder::class,
            TagSeeder::class,
            DestinationSeeder::class,
            HotelSeeder::class,
            RestaurantSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}