<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoanApplicationDetail>
 */
class LoanApplicationDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->numberBetween(1000, 10000),
            'term' => $this->faker->numberBetween(1, 12),
            'rate' => $this->faker->numberBetween(1, 10),
            'quota' => $this->faker->randomFloat(2, 100, 1000),
            'frecuency' => $this->faker->randomElement(["daily", "weekly", "bi-weekly", "fortnightly", "monthly"]),
            'purpose' => $this->faker->sentence,
            'customer_comment' => $this->faker->sentence,
            'loan_application_id' => $this->faker->randomElement(\App\Models\LoanApplication::pluck('id')),

        ];
    }
}
