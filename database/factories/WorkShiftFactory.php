<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class WorkShiftFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'                             => fake()->unique()->words(2, true) . ' shift',
            'start_time'                       => '08:00:00',
            'end_time'                         => '17:00:00',
            'crosses_midnight'                 => false,
            'late_tolerance_minutes'           => 15,
            'early_checkout_tolerance_minutes' => 15,
            'working_days'                     => [1, 2, 3, 4, 5],
            'is_active'                        => true,
        ];
    }
}