<?php
namespace Database\Factories;

use App\Models\LeaveType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaveRequestFactory extends Factory
{
    public function definition(): array
    {
        $start = fake()->dateTimeBetween('now', '+30 days');
        $end   = fake()->dateTimeBetween($start, '+40 days');

        return [
            'user_id'       => User::factory(),
            'leave_type_id' => LeaveType::factory(),
            'start_date'    => $start->format('Y-m-d'),
            'end_date'      => $end->format('Y-m-d'),
            'total_days'    => fake()->numberBetween(1, 10),
            'reason'        => fake()->sentence(),
            'status'        => 'pending',
        ];
    }
}