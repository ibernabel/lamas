<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Phone>
 */
class PhoneFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country_area' => $this->faker->countryCode(),
            'number' => $this->faker->phoneNumber(),
            'extension' => $this->faker->optional()->randomDigit(),
            'type' => $this->faker->randomElement(['home', 'work', 'mobile']),
            
        ];
    }
}
