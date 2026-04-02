<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => fake()->unique()->jobTitle() . ' Dept',
            'code'        => strtoupper(fake()->unique()->lexify('???')),
            'description' => fake()->sentence(),
            'is_active'   => true,
        ];
    }
}