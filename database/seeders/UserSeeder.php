<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Muhammad Rizky Ibrahim',
            'username' => 'rizkyxibrahim',
            'email'    => 'rizkyxibrahim@gmail.com',
            'phone'    => '085179706698',
            'password' => Hash::make('P@ssw0rd'),
            'role'     => 'admin',
            'email_verified_at' => now(),
            'profile_photo_path' => null,
        ]);
        // Create additional test users (optional)
        User::factory()->count(10)->create();
    }
}
