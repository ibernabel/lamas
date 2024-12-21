<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promoter>
 */
class PromoterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone_number' => $this->faker->unique()->phoneNumber(),
            'NID' => $this->faker->unique()->numerify('##############'),
            'bonus_type' => $this->faker->randomElement(['percentage', 'fixed']),
            'bonus_value' => $this->faker->randomNumber(2),
            'bank_account_number' => $this->faker->numerify('##############'),
            'bank_name' => $this->faker->company(),
            'bank_account_name' => $this->faker->name(),
            'bank_account_type' => $this->faker->randomElement(['savings', 'current']),
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
