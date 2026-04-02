<?php
namespace Database\Factories;

use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        $date    = fake()->dateTimeBetween('-30 days', 'now');
        $checkIn = fake()->dateTimeBetween($date->format('Y-m-d') . ' 07:30', $date->format('Y-m-d') . ' 09:30');

        return [
            'user_id'                  => User::factory(),
            'work_shift_id'            => WorkShift::factory(),
            'attendance_date'          => $date->format('Y-m-d'),
            'check_in_time'            => $checkIn,
            'check_in_latitude'        => -6.2088,
            'check_in_longitude'       => 106.8456,
            'check_in_gps_valid'       => true,
            'check_in_distance_meters' => fake()->numberBetween(0, 80),
            'status'                   => fake()->randomElement(['present', 'late', 'present', 'present']),
            'late_minutes'             => 0,
            'working_minutes'          => fake()->numberBetween(420, 540),
        ];
    }

    public function absent(): static
    {
        return $this->state([
            'check_in_time'  => null,
            'check_out_time' => null,
            'status'         => 'absent',
            'working_minutes'=> 0,
        ]);
    }
}
