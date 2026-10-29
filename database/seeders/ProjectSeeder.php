<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('username', 'rizkyxibrahim')->first();

        if (! $user) {
            return;
        }

        $projects = [
            [
                'title'       => 'Xibra API',
                'description' => 'Central REST API for Xibra Platform — authentication, portfolio, dashboard, and personal modules.',
                'image_url'   => null,
                'github_url'  => 'https://github.com/rizkyxibrahim/xibra-api',
                'live_url'    => null,
                'start_date'  => '2026-01-01',
                'end_date'    => null,
                'is_featured' => true,
                // 'is_published' => true,  // REMOVE THIS LINE
            ],
            [
                'title'       => 'Xibra Web',
                'description' => 'Portfolio website and private dashboard built with Nuxt 3 and Tailwind CSS v4.',
                'image_url'   => null,
                'github_url'  => 'https://github.com/rizkyxibrahim/portfolio-web',
                'live_url'    => null,
                'start_date'  => '2026-02-01',
                'end_date'    => null,
                'is_featured' => true,
                // 'is_published' => true,  // REMOVE THIS LINE
            ],
            [
                'title'       => 'Xibra Bot',
                'description' => 'Telegram bot integration with Hermes Agent for personal productivity and finance.',
                'image_url'   => null,
                'github_url'  => 'https://github.com/rizkyxibrahim/xibra-bot',
                'live_url'    => null,
                'start_date'  => '2026-03-01',
                'end_date'    => null,
                'is_featured' => false,
                'is_published' => true
            ],
        ];

        foreach ($projects as $project) {
            Project::create(array_merge($project, [
                'user_id' => $user->id,
            ]));
        }
    }
}
