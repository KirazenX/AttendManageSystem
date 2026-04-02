<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WorkShift extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'start_time', 'end_time', 'crosses_midnight',
        'late_tolerance_minutes', 'early_checkout_tolerance_minutes',
        'working_days', 'is_active', 'description',
    ];

    protected $casts = [
        'crosses_midnight' => 'boolean',
        'is_active'        => 'boolean',
        'working_days'     => 'array',
    ];

    public function schedules()
    {
        return $this->hasMany(WorkSchedule::class);
    }

    public function isActiveOnDay(int $dayOfWeek): bool
    {
        return in_array($dayOfWeek, $this->working_days ?? []);
    }
}
