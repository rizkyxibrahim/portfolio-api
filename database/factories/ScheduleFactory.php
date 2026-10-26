<?php

namespace Database\Factories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->sentence(),
            'start_time' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            'end_time' => $this->faker->optional()->dateTimeBetween('+1 day', '+30 days'),
            'location' => $this->faker->optional()->city,
            'is_all_day' => $this->faker->boolean(10),
            'recurring' => $this->faker->boolean(30),
            'recurring_pattern' => $this->faker->optional()->randomElement(['daily', 'weekdays', 'weekly', 'monthly']),
            'timezone' => 'Asia/Jakarta',
            'is_overnight' => $this->faker->boolean(10),
            'user_id' => null,
        ];
    }
}