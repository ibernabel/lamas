<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoanApplication>
 */
class LoanApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => $this->faker->randomElement(['received', 'verified',
            'assigned', 'analyzed', 'approved', 'rejected', 'archived']),
            'changed_status_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'is_answered' => $this->faker->boolean,
            'is_approved' => $this->faker->boolean,
            'is_rejected' => $this->faker->boolean,
            'is_archived' => $this->faker->boolean,
            'is_new' => $this->faker->boolean,
            'customer_id' => $this->faker->randomElement(\App\Models\Customer::pluck('id'))
        ];
    }
}
