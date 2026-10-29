<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@xibra.local'],
            [
                'name' => 'Admin Xibra',
                'password' => Hash::make('password'),
            ]
        );

        Profile::firstOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => 'Rizky Xibrahim',
                'headline' => 'Software Engineer | AI Researcher | Personal Platform Builder',
                'bio' => 'Membangun Xibra Platform — ekosistem digital pribadi yang menggabungkan personal AI assistant, productivity system, developer platform, dan personal portfolio.',
                'location' => 'Indonesia',
                'timezone' => 'Asia/Jakarta',
                'social_links' => [
                    'github' => 'https://github.com/rizkyxibrahim',
                    'linkedin' => 'https://linkedin.com/in/rizkyxibrahim',
                ],
            ]
        );
    }
}