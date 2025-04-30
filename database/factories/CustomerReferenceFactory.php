<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CustomerReferenceFactory extends Factory
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
            'NID' => $this->faker->unique()->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail(),
            'relationship' => $this->faker->word(),
            'reference_since' => $this->faker->dateTimeBetween('-5 years', 'now'),
            'is_active' => $this->faker->boolean(),
            'occupation' => $this->faker->word(),
            'is_who_referred' => $this->faker->boolean(),
            'type' => $this->faker->randomElement(['personal','professional','guarantor','academic','commercial','credit','banking','tenant','character','technical','client','supplier','community','other']),
            'customer_id' => $this->faker->randomElement(\App\Models\Customer::pluck('id')),
        ];
    }
}
