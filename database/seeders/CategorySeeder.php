<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            // Kategori utama bisnis
            [
                'name' => 'Destinasi',
                'slug' => 'destinasi',
                'description' => 'Kategori utama untuk destinasi wisata',
                'icon' => '📍',
            ],
            [
                'name' => 'Hotel',
                'slug' => 'hotel',
                'description' => 'Kategori utama untuk hotel',
                'icon' => '🏨',
            ],
            [
                'name' => 'Restoran',
                'slug' => 'restoran',
                'description' => 'Kategori utama untuk restoran',
                'icon' => '🍽️',
            ],
            [
                'name' => 'Wisata Mitra',
                'slug' => 'wisata-mitra',
                'description' => 'Destinasi yang dikelola oleh mitra ExploreNesia',
                'icon' => '📍',
            ],

            // Lokasi/Tempat
            ['name' => 'Indoor', 'slug' => 'indoor', 'description' => 'Tempat dalam ruangan', 'icon' => '🏠'],
            ['name' => 'Outdoor', 'slug' => 'outdoor', 'description' => 'Tempat luar ruangan', 'icon' => '🏕️'],
            ['name' => 'Pantai', 'slug' => 'pantai', 'description' => 'Destinasi pantai', 'icon' => '🏖️'],
            ['name' => 'Gunung', 'slug' => 'gunung', 'description' => 'Destinasi pegunungan', 'icon' => '⛰️'],
            ['name' => 'Bukit', 'slug' => 'bukit', 'description' => 'Destinasi perbukitan', 'icon' => '🌄'],
            ['name' => 'Hutan', 'slug' => 'hutan', 'description' => 'Destinasi hutan', 'icon' => '🌳'],
            ['name' => 'Danau', 'slug' => 'danau', 'description' => 'Destinasi danau', 'icon' => '🌊'],
            ['name' => 'Air Terjun', 'slug' => 'air-terjun', 'description' => 'Destinasi air terjun', 'icon' => '💦'],
            ['name' => 'Sungai', 'slug' => 'sungai', 'description' => 'Destinasi sungai', 'icon' => '🌊'],
            ['name' => 'Goa', 'slug' => 'goa', 'description' => 'Destinasi goa', 'icon' => '🕳️'],

            // Aktivitas
            ['name' => 'Trekking', 'slug' => 'trekking', 'description' => 'Cocok untuk trekking', 'icon' => '🥾'],
            ['name' => 'Camping', 'slug' => 'camping', 'description' => 'Cocok untuk camping', 'icon' => '🏕️'],
            ['name' => 'Snorkeling', 'slug' => 'snorkeling', 'description' => 'Cocok untuk snorkeling', 'icon' => '🤿'],
            ['name' => 'Diving', 'slug' => 'diving', 'description' => 'Cocok untuk diving', 'icon' => '🐟'],
            ['name' => 'Fotografi', 'slug' => 'fotografi', 'description' => 'Spot fotografi menarik', 'icon' => '📸'],
            ['name' => 'Sunset', 'slug' => 'sunset', 'description' => 'Spot sunset terbaik', 'icon' => '🌅'],
            ['name' => 'Sunrise', 'slug' => 'sunrise', 'description' => 'Spot sunrise terbaik', 'icon' => '🌅'],
            ['name' => 'Keluarga', 'slug' => 'keluarga', 'description' => 'Cocok untuk keluarga', 'icon' => '👨‍👩‍👧‍👦'],
            ['name' => 'Romantis', 'slug' => 'romantis', 'description' => 'Cocok untuk pasangan', 'icon' => '💑'],
            ['name' => 'Petualangan', 'slug' => 'petualangan', 'description' => 'Cocok untuk petualangan', 'icon' => '🧗'],

            // Fasilitas
            ['name' => 'Parkir Luas', 'slug' => 'parkir-luas', 'description' => 'Tersedia parkir luas', 'icon' => '🚙'],
            ['name' => 'Toilet', 'slug' => 'toilet', 'description' => 'Tersedia toilet', 'icon' => '🚻'],
            ['name' => 'Warung Makan', 'slug' => 'warung-makan', 'description' => 'Tersedia warung makan', 'icon' => '🍜'],
            ['name' => 'Penginapan', 'slug' => 'penginapan', 'description' => 'Tersedia penginapan', 'icon' => '🏨'],
            ['name' => 'WiFi', 'slug' => 'wifi', 'description' => 'Tersedia WiFi', 'icon' => '📶'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug'] ?? Str::slug($category['name'])],
                $category
            );
        }
    }
}
