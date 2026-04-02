<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name', 'email', 'password', 'employee_id', 'phone',
        'avatar', 'department_id', 'gender', 'join_date', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'join_date'         => 'date',
        'is_active'         => 'boolean',
    ];

    public function department()   { return $this->belongsTo(Department::class); }
    public function attendances()  { return $this->hasMany(Attendance::class); }
    public function workSchedules(){ return $this->hasMany(WorkSchedule::class); }
    public function leaveRequests(){ return $this->hasMany(LeaveRequest::class); }
    public function gpsValidations(){ return $this->hasMany(GpsValidation::class); }

    public function activeSchedule()
    {
        return $this->workSchedules()
            ->where('is_active', true)
            ->where('effective_date', '<=', now()->toDateString())
            ->where(function ($q) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', now()->toDateString());
            })
            ->with('workShift')
            ->latest('effective_date')
            ->first();
    }

    public function todayAttendance()
    {
        return $this->attendances()
            ->where('attendance_date', now()->toDateString())
            ->first();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
