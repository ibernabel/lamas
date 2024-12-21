<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerJobInfo>
 */
class CustomerJobInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'role' => $this->faker->jobTitle(),
            'level' => $this->faker->word,
            'start_date' => $this->faker->date(),
            'salary' => $this->faker->randomFloat(2, 0, 999999),
            'payment_type' => $this->faker->word,
            'payment_frequency' => $this->faker->randomElements(["daily", "weekly", "bi-weekly", "fortnightly", "monthly"]),
            'payment_bank' => $this->faker->company(),
            'payment_account_number' => $this->faker->numerify('############'),
            'schedule' => $this->faker->word,
            'supervisor_name' => $this->faker->name(),
        ];
    }
}
