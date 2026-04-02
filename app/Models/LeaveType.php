<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'max_days_per_year', 'requires_attachment', 'is_active', 'description',
    ];

    protected $casts = [
        'max_days_per_year'   => 'integer',
        'requires_attachment' => 'boolean',
        'is_active'           => 'boolean',
    ];

    public function requests()
    {
        return $this->hasMany(LeaveRequest::class);
    }
}
