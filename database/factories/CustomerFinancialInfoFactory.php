<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFinancialInfoFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    return [
      'other_incomes' => $this->faker->randomFloat(2, 0, 999999),
      'total_incomes' => $this->faker->randomFloat(2, 0, 999999),
      'discounts' => $this->faker->randomFloat(2, 0, 999999),
      'housing_type' => $this->faker->randomElement(['rented', 'owned', 'financed', 'borrowed']),
      'monthly_housing_payment' => $this->faker->randomFloat(2, 0, 999999),
      'total_debts' => $this->faker->randomFloat(2, 0, 999999),
      'loan_installments' => $this->faker->randomFloat(2, 0, 999999),
      'household_expenses' => $this->faker->randomFloat(2, 0, 999999),
      'labor_benefits' => $this->faker->randomFloat(2, 0, 999999),
      'guarantee_assets' => $this->faker->randomFloat(2, 0, 999999),
      'mode_of_transport' => $this->faker->randomElement(['public_transportation', 'own_car', 'own_motorcycle', 'bicycle', 'other']),
      'customer_id' => $this->faker->randomElement(\App\Models\Customer::pluck('id')),

    ];
  }
}
