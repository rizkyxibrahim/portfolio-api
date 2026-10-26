<?php

namespace Database\Factories;

use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    protected $model = Todo::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->sentence(),
            'is_completed' => $this->faker->boolean(20),
            'scheduled_start_at' => $this->faker->optional()->dateTimeBetween('+1 day', '+30 days'),
            'scheduled_end_at' => null,
            'due_at' => $this->faker->optional()->dateTimeBetween('+30 days', '+60 days'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'user_id' => null,
        ];
    }
}