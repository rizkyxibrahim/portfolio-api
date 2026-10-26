<?php

namespace Database\Factories;

use App\Models\FinanceTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class FinanceTransactionFactory extends Factory
{
    protected $model = FinanceTransaction::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['income', 'expense']),
            'amount' => $this->faker->randomFloat(2, 1000, 10000000),
            'category' => $this->faker->word(),
            'description' => $this->faker->optional()->sentence(),
            'transaction_date' => $this->faker->dateTimeBetween('-6 months', 'today'),
            'user_id' => null,
        ];
    }
}