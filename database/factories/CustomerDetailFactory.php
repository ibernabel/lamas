<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerDetail>
 */
class CustomerDetailFactory extends Factory
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
      'education_level' => $this->faker->randomElement(['primary','secondary','high_school', 'bachelor', 'postgraduate', 'master', 'doctorate']),
      'nationality' => $this->faker->randomElement([
        'American',
        'British',
        'Canadian',
        'Chinese',
        'Dominican',
        'French',
        'German',
        'Indian',
        'Italian',
        'Japanese',
        'Mexican',
        'Russian',
        'Spanish',
        'Australian',
        'Brazilian',
        'Dutch',
        'Argentine',
        'Colombian',
        'Peruvian',
        'Chilean',
        'Venezuelan',
        'Uruguayan',
        'Paraguayan',
        'Bolivian',
        'Ecuadorian',
        'Costa Rican',
        'Panamanian',
        'Nicaraguan',
        'Honduran',
        'Salvadoran',
        'Guatemalan'
      ]),
      'housing_type' => $this->faker->randomElement(['owned', 'rented', 'mortgaged']),
      'move_in_date' => $this->faker->date,
      'vehicle_type' => $this->faker->randomElement(['owned', 'rented', 'financed']),
      'customer_id' => $this->faker->randomElement(\App\Models\Customer::pluck('id')),
    ];
  }
}
