<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\City;

class ProvinceAndCitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $regions = [
            'Banten' => ['Cilegon', 'Serang', 'Tangerang', 'Tangerang Selatan'],
            'DKI Jakarta' => ['Jakarta Barat', 'Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Timur', 'Jakarta Utara'],
            'Jawa Barat' => ['Bandung', 'Banjar', 'Bekasi', 'Bogor', 'Cimahi', 'Cirebon', 'Depok', 'Sukabumi', 'Tasikmalaya'],
            'Jawa Tengah' => ['Magelang', 'Pekalongan', 'Salatiga', 'Semarang', 'Surakarta (Solo)', 'Tegal'],
            'DI Yogyakarta' => ['Yogyakarta'],
            'Jawa Timur' => ['Batu', 'Blitar', 'Kediri', 'Madiun', 'Malang', 'Mojokerto', 'Pasuruan', 'Probolinggo', 'Surabaya']
        ];

        $provinceCodes = [
            'Banten' => 'BT',
            'DKI Jakarta' => 'JK',
            'Jawa Barat' => 'JB',
            'Jawa Tengah' => 'JT',
            'DI Yogyakarta' => 'YK',
            'Jawa Timur' => 'JI',
        ];

        foreach ($regions as $provinceName => $cities) {
            // Create or get province
            $province = Province::firstOrCreate(
                ['name' => $provinceName],
                ['code' => $provinceCodes[$provinceName]]
            );

            // Create cities for this province
            foreach ($cities as $cityName) {
                City::firstOrCreate(
                    ['name' => $cityName, 'province_id' => $province->id],
                    []
                );
            }
        }

        $this->command->info('Provinces and cities seeded successfully!');
    }
}