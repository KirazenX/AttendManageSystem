<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GpsValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'attendance_id', 'office_location_id', 'type',
        'user_latitude', 'user_longitude', 'distance_meters', 'is_valid',
    ];

    protected $casts = [
        'user_latitude'  => 'float',
        'user_longitude' => 'float',
        'distance_meters'=> 'integer',
        'is_valid'       => 'boolean',
    ];

    public function user()       { return $this->belongsTo(User::class); }
    public function attendance() { return $this->belongsTo(Attendance::class); }
    public function office()     { return $this->belongsTo(OfficeLocation::class, 'office_location_id'); }
}
