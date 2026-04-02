<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'work_shift_id', 'attendance_date',
        'check_in_time', 'check_in_latitude', 'check_in_longitude',
        'check_in_photo', 'check_in_gps_valid', 'check_in_distance_meters',
        'check_out_time', 'check_out_latitude', 'check_out_longitude',
        'check_out_photo', 'check_out_gps_valid',
        'status', 'late_minutes', 'working_minutes', 'notes',
    ];

    protected $casts = [
        'attendance_date'     => 'date',
        'check_in_time'       => 'datetime',
        'check_out_time'      => 'datetime',
        'check_in_gps_valid'  => 'boolean',
        'check_out_gps_valid' => 'boolean',
        'check_in_latitude'   => 'float',
        'check_in_longitude'  => 'float',
        'check_out_latitude'  => 'float',
        'check_out_longitude' => 'float',
    ];

    public function user()          { return $this->belongsTo(User::class); }
    public function workShift()     { return $this->belongsTo(WorkShift::class); }
    public function gpsValidations(){ return $this->hasMany(GpsValidation::class); }

    public function isCheckedIn(): bool  { return $this->check_in_time !== null; }
    public function isCheckedOut(): bool { return $this->check_out_time !== null; }
}
