<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveTypeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'                => fake()->unique()->words(2, true) . ' leave',
            'code'                => strtoupper(fake()->unique()->lexify('??')),
            'max_days_per_year'   => fake()->numberBetween(5, 30),
            'requires_attachment' => fake()->boolean(30),
            'is_active'           => true,
        ];
    }
}