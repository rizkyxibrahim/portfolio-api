<?php

namespace Database\Seeders;

use App\Models\Certification;
use App\Models\Education;
use App\Models\Experience;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $profile = Profile::first();

        if (! $profile) {
            return;
        }

        // Skill contoh
        Skill::insert([
            [
                'profile_id' => $profile->id,
                'name' => 'Laravel',
                'category' => 'Backend Framework',
                'level' => 85,
                'is_featured' => true,
                'display_order' => 1,
            ],
            [
                'profile_id' => $profile->id,
                'name' => 'Nuxt.js',
                'category' => 'Frontend Framework',
                'level' => 80,
                'is_featured' => true,
                'display_order' => 2,
            ],
            [
                'profile_id' => $profile->id,
                'name' => 'PostgreSQL',
                'category' => 'Database',
                'level' => 75,
                'is_featured' => false,
                'display_order' => 3,
            ],
        ]);

        // Experience contoh
        Experience::create([
            'profile_id' => $profile->id,
            'company' => 'Xibra Research',
            'position' => 'Founder & Lead Developer',
            'description' => 'Membangun Xibra Platform dan ecosistem digital pribadi.',
            'start_date' => '2026-01-01',
            'end_date' => null,
            'is_current' => true,
            'location' => 'Indonesia',
            'display_order' => 1,
        ]);

        // Project contoh
        Project::create([
            'profile_id' => $profile->id,
            'title' => 'Xibra API',
            'slug' => 'xibra-api',
            'excerpt' => 'Backend API untuk Xibra Platform dengan Laravel dan PostgreSQL.',
            'content' => 'REST API yang menjadi fondasi Xibra Platform. Menyediakan autentikasi Sanctum, manajemen portfolio, dan integrasi agen AI Hermes.',
            'image' => null,
            'demo_url' => 'http://localhost:8000',
            'github_url' => 'https://github.com/rizkyxibrahim/xibra-api',
            'status' => 'published',
            'is_featured' => true,
            'published_at' => now(),
            'display_order' => 1,
        ]);
    }
}