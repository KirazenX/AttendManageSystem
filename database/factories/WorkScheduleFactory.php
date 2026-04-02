<?php
namespace Database\Factories;

use App\Models\User;
use App\Models\WorkShift;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'        => User::factory(),
            'work_shift_id'  => WorkShift::factory(),
            'effective_date' => now()->subDays(30)->toDateString(),
            'end_date'       => null,
            'is_active'      => true,
        ];
    }
}