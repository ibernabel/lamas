<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoanApplicationNote>
 */
class LoanApplicationNoteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'note' => $this->faker->sentence,
            'user_id' => $this->faker->randomElement(\App\Models\User::pluck('id')),
            'loan_application_id' => $this->faker->randomElement(\App\Models\LoanApplication::pluck('id')),
        ];
    }
}
