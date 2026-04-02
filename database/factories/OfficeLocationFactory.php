<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeLocationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'          => fake()->company() . ' Office',
            'address'       => fake()->address(),
            'latitude'      => fake()->latitude(-6.4, -6.1),
            'longitude'     => fake()->longitude(106.7, 107.0),
            'radius_meters' => 100,
            'is_active'     => true,
        ];
    }
}
