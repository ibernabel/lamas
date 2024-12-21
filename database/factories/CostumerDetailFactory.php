<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CostumerDetail>
 */
class CostumerDetailFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'first_name' => $this->faker->firstName,
      'last_name' => $this->faker->lastName,
      'email' => $this->faker->email,
      'nickname' => $this->faker->userName,
      'birthday' => $this->faker->date,
      'gender' => $this->faker->randomElement(['male', 'female', 'other']),
      'marital_status' => $this->faker->randomElement(['single', 'married', 'divorced', 'widowed', 'other']),
      'education_level' => $this->faker->randomElement(['high_school', 'bachelor', 'master', 'doctoral', 'other']),
      'costumer_id' => $this->faker->numberBetween(1, 50),
    ];
  }
}
