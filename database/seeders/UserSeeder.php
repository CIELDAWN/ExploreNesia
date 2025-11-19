<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    public function run()
    {
        // User Demo User
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password123'),
            'role' => 'user',
            'phone' => '+6281234567890',
            'address' => 'Jl. Merdeka No. 123, Jakarta Pusat',
            'avatar' => null,
            'is_active' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Admin Demo
        User::create([
            'name' => 'Admin ExploreNesia',
            'email' => 'admin@explorenesia.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '+6281122334455',
            'address' => 'Jl. Sudirman Kav. 1, Jakarta Selatan',
            'avatar' => null,
            'is_active' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Mitra Demo Mitra
        User::create([
            'name' => 'Mitra Bali Hotel',
            'email' => 'mitra.bali@explorenesia.com',
            'email_verified_at' => Carbon::now(),
            'password' => Hash::make('password123'),
            'role' => 'mitra',
            'phone' => '+6281876543210',
            'address' => 'Jl. Legian No. 88, Kuta, Bali',
            'avatar' => null,
            'is_active' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        
        $this->command->info('Demo users created successfully!');
        $this->command->info('Admin: admin@explorenesia.com / password123');
        $this->command->info('User: budi@example.com / password123');
        $this->command->info('Mitra: mitra.bali@explorenesia.com / password123');
    }
}
