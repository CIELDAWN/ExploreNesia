<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ProvinceSeeder::class,
            CitySeeder::class,
            CategorySeeder::class,
            DestinationSeeder::class,
            HotelSeeder::class,
            RestaurantSeeder::class,
            ReviewSeeder::class,
        ]);
    }
}