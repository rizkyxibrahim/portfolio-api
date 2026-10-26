<?php

namespace Database\Factories;

use App\Models\Reminder;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReminderFactory extends Factory
{
    protected $model = Reminder::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional()->sentence(),
            'reminder_at' => $this->faker->dateTimeBetween('+1 day', '+30 days'),
            'is_sent' => false,
            'is_completed' => false,
            'recurring' => $this->faker->boolean(30),
            'recurring_pattern' => $this->faker->optional()->randomElement(['daily', 'weekdays', 'weekly', 'before_event']),
            'delivery_status' => 'pending',
            'retry_count' => 0,
            'max_retries' => 3,
            'dedupe_key' => null,
            'user_id' => null,
        ];
    }
}