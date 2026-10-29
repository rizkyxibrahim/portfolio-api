<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Skill;

class SkillSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('username', 'rizkyxibrahim')->first();

        if (! $user) {
            return;
        }

        $skills = [
            [
                'name'                 => 'PHP',
                'category'             => 'programming',
                'level'                => 'advanced',
                'years_of_experience'  => 5,
            ],
            [
                'name'                 => 'Laravel',
                'category'             => 'framework',
                'level'                => 'advanced',
                'years_of_experience'  => 4,
            ],
            [
                'name'                 => 'Nuxt.js / Vue.js',
                'category'             => 'frontend',
                'level'                => 'intermediate',
                'years_of_experience'  => 3,
            ],
            [
                'name'                 => 'PostgreSQL',
                'category'             => 'database',
                'level'                => 'intermediate',
                'years_of_experience'  => 3,
            ],
            [
                'name'                 => 'Tailwind CSS',
                'category'             => 'styling',
                'level'                => 'intermediate',
                'years_of_experience'  => 2,
            ],
            [
                'name'                 => 'Git',
                'category'             => 'tooling',
                'level'                => 'advanced',
                'years_of_experience'  => 4,
            ],
        ];

        foreach ($skills as $skill) {
            Skill::create(array_merge($skill, [
                'user_id' => $user->id,
            ]));
        }
    }
}

