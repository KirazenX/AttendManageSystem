<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'leave_type_id', 'start_date', 'end_date', 'total_days',
        'reason', 'attachment', 'status', 'approved_by', 'approved_at', 'rejection_reason',
    ];

    protected $casts = [
        'start_date'  => 'date',
        'end_date'    => 'date',
        'approved_at' => 'datetime',
        'total_days'  => 'integer',
    ];

    public function user()       { return $this->belongsTo(User::class); }
    public function leaveType()  { return $this->belongsTo(LeaveType::class); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
}
