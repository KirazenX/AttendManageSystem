<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'work_shift_id', 'effective_date', 'end_date', 'is_active', 'notes',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'end_date'       => 'date',
        'is_active'      => 'boolean',
    ];

    public function user()      { return $this->belongsTo(User::class); }
    public function workShift() { return $this->belongsTo(WorkShift::class); }
}
