<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CustomerVehicle>
 */
class CustomerVehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'vehicle_brand' => $this->faker->randomElement(['Toyota', 'Honda', 'Ford', 'Chevrolet', 'Nissan']),
            'vehicle_model' => $this->faker->randomElement(['Corolla', 'Civic', 'F-150', 'Malibu', 'Altima']),
            'vehicle_year' => $this->faker->year(),
            'vehicle_color' => $this->faker->randomElement(['Red', 'Blue', 'Green', 'Black', 'White']),
            'vehicle_plate' => $this->faker->unique()->bothify('??-###'),
            'vehicle_possession_type' => $this->faker->randomElement(['owned', 'leased', 'rented', 'shared']),
            'is_financed' => $this->faker->boolean(),
            'is_owned' => $this->faker->boolean(),
            'is_leased' => $this->faker->boolean(),
            'is_rented' => $this->faker->boolean(),
            'is_shared' => $this->faker->boolean(),
            'vehicle_type' => $this->faker->randomElement(['Sedan', 'SUV', 'Truck', 'Van', 'Coupe', 'bike', 'motorcycle', 'other']),
            'vehicle_purpose' => $this->faker->randomElement(['personal', 'business', 'commercial']),
            'vehicle_mileage' => $this->faker->numberBetween(0, 200000),
            'vehicle_value' => $this->faker->numberBetween(5000, 50000),
            'vehicle_condition' => $this->faker->randomElement(['new', 'used', 'damaged']),
            'is_insured' => $this->faker->boolean(),
            'insurance_company' => $this->faker->randomElement(['Geico', 'State Farm', 'Allstate', 'Progressive', 'Farmers']),
            'has_gps' => $this->faker->boolean(),
            'gps_company' => $this->faker->randomElement(['Verizon', 'AT&T', 'T-Mobile', 'Sprint']),
            'customer_id' => $this->faker->randomElement(\App\Models\Customer::pluck('id')),

        ];
    }
}
