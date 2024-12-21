<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'street' => $this->faker->streetName(),
            'street2' => $this->faker->optional()->streetName(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'type' => $this->faker->randomElement(['home', 'work', 'billing', 'shipping']),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'references' => $this->faker->optional()->text(200),
        ];
    }
}
