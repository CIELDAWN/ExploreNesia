<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            // Lokasi/Tempat
            ['name' => 'Indoor', 'description' => 'Tempat dalam ruangan', 'color' => '#8B5CF6'],
            ['name' => 'Outdoor', 'description' => 'Tempat luar ruangan', 'color' => '#10B981'],
            ['name' => 'Pantai', 'description' => 'Destinasi pantai', 'color' => '#06B6D4'],
            ['name' => 'Gunung', 'description' => 'Destinasi pegunungan', 'color' => '#6366F1'],
            ['name' => 'Bukit', 'description' => 'Destinasi perbukitan', 'color' => '#84CC16'],
            ['name' => 'Hutan', 'description' => 'Destinasi hutan', 'color' => '#059669'],
            ['name' => 'Danau', 'description' => 'Destinasi danau', 'color' => '#0284C7'],
            ['name' => 'Air Terjun', 'description' => 'Destinasi air terjun', 'color' => '#0EA5E9'],
            ['name' => 'Sungai', 'description' => 'Destinasi sungai', 'color' => '#3B82F6'],
            ['name' => 'Goa', 'description' => 'Destinasi goa', 'color' => '#7C3AED'],
            
            // Aktivitas
            ['name' => 'Trekking', 'description' => 'Cocok untuk trekking', 'color' => '#F59E0B'],
            ['name' => 'Camping', 'description' => 'Cocok untuk camping', 'color' => '#10B981'],
            ['name' => 'Snorkeling', 'description' => 'Cocok untuk snorkeling', 'color' => '#06B6D4'],
            ['name' => 'Diving', 'description' => 'Cocok untuk diving', 'color' => '#3B82F6'],
            ['name' => 'Fotografi', 'description' => 'Spot fotografi menarik', 'color' => '#EC4899'],
            ['name' => 'Sunset', 'description' => 'Spot sunset terbaik', 'color' => '#F97316'],
            ['name' => 'Sunrise', 'description' => 'Spot sunrise terbaik', 'color' => '#F59E0B'],
            ['name' => 'Keluarga', 'description' => 'Cocok untuk keluarga', 'color' => '#8B5CF6'],
            ['name' => 'Romantis', 'description' => 'Cocok untuk pasangan', 'color' => '#EC4899'],
            ['name' => 'Petualangan', 'description' => 'Cocok untuk petualangan', 'color' => '#EF4444'],
            
            // Fasilitas
            ['name' => 'Parkir Luas', 'description' => 'Tersedia parkir luas', 'color' => '#6B7280'],
            ['name' => 'Toilet', 'description' => 'Tersedia toilet', 'color' => '#6B7280'],
            ['name' => 'Warung Makan', 'description' => 'Tersedia warung makan', 'color' => '#F59E0B'],
            ['name' => 'Penginapan', 'description' => 'Tersedia penginapan', 'color' => '#8B5CF6'],
            ['name' => 'WiFi', 'description' => 'Tersedia WiFi', 'color' => '#3B82F6'],
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($tag['name'])],
                $tag
            );
        }
    }
}


