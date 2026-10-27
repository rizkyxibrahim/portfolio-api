<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create default admin user
        User::firstOrCreate(
            ['username' => 'rizkyxibrahim'],
            [
                'name' => 'Rizky Xibra Ibrahim',
                'username' => 'rizkyxibrahim',
                'email' => 'rizky@example.com',
                'phone' => '08123456789',
                'password' => bcrypt('password123'),
                'email_verified_at' => now(),
                'profile_photo_path' => null,
            ]
        );

        // Create additional test users (optional)
        User::factory()->count(10)->create();
    }
}