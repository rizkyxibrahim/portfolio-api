<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Rizky Xibra Ibrahim',
            'username' => 'rizkyxibrahim',
            'email' => 'rizky@xibrahim.com',
            'phone' => '+628123456789',
            'password' => bcrypt('password123'),
            'profile_photo_path' => null,
        ]);

        User::factory()->count(10)->create();
    }
}