<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('username', 'rizkyxibrahim')->first();

        if (! $user) {
            return;
        }

        DB::table('profiles')->insert([
            'user_id'      => $user->id,
            'full_name'    => 'Muhammad Rizky Ibrahim',
            'headline'     => 'Developer & Owner Xibra Platform',
            'bio'          => 'Membangun ekosistem digital pribadi dengan Laravel, Nuxt, dan Telegram.',
            'avatar'       => null,
            'resume_url'   => null,
            'location'     => 'Indonesia',
            'phone'        => '085179706698',
            'email'        => 'rizkyxibrahim@gmail.com',
            'website'      => null,
            'timezone'     => 'Asia/Jakarta',
            'social_links' => json_encode([
                'github'    => 'https://github.com/rizkyxibrahim',
                'linkedin'  => null,
            ]),
            'created_at'   => now(),
            'updated_at'   => now(),
        ]);
    }
}
