<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'NID' => $this->faker->unique()->numerify('###########'),
            'lead_channel' => $this->faker->randomElement(['Facebook', 'Instagram', 'Twitter', 'LinkedIn', 'WhatsApp', 'Telegram', 'Email', 'Phone', 'SMS', 'Other']),
            'is_referred' => $this->faker->boolean(),
            'referred_by' => $this->faker->optional()->numerify('############'),
            'is_active' => $this->faker->boolean(),
            'is_assigned' => $this->faker->boolean(),
            'portfolio_id' => $this->faker->randomElement(\App\Models\Portfolio::pluck('id')),
            'promoter_id' => $this->faker->optional()->randomElement(\App\Models\Promoter::pluck('id')),
        ];
    }
}
