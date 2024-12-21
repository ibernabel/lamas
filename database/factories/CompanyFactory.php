<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'type' => $this->faker->randomElement(['SRL', 'SA', 'SAS']),
            'rnc' => $this->faker->unique()->numerify('##########'),
            'website' => $this->faker->url(),
            'departmet' => $this->faker->state(),
            'branch' => $this->faker->randomElement(['IT', 'Finance', 'Marketing', 'Sales', 'HR']),
            'customer_id' => $this->faker->numberBetween(1, 50),
        ];
    }
}
