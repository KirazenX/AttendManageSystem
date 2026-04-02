<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => bcrypt('password'),
            'employee_id'       => 'EMP' . fake()->unique()->numerify('###'),
            'phone'             => fake()->phoneNumber(),
            'gender'            => fake()->randomElement(['male', 'female']),
            'join_date'         => fake()->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
            'is_active'         => true,
            'remember_token'    => Str::random(10),
        ];
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }
}