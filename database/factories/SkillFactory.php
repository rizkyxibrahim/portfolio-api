<?php

namespace Database\Factories;

use App\Models\Skill;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkillFactory extends Factory
{
    protected $model = Skill::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['PHP', 'JavaScript', 'Python', 'Laravel', 'Vue.js', 'React']),
            'category' => $this->faker->randomElement(['programming', 'design', 'language', 'management']),
            'level' => $this->faker->randomElement(['beginner', 'intermediate', 'advanced', 'expert']),
            'years_of_experience' => $this->faker->numberBetween(0, 20),
            'user_id' => null,
        ];
    }
}
