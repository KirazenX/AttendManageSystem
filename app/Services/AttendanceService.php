<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class AttendanceService
{
    public function __construct(protected GpsValidationService $gpsService) {}

    public function checkIn(User $user, array $data): Attendance
    {
        $existing = Attendance::where('user_id', $user->id)
            ->where('attendance_date', now()->toDateString())
            ->first();

        if ($existing) {
            throw new \App\Exceptions\AttendanceException('Already checked in today.');
        }

        $schedule = $user->activeSchedule();
        $shift    = $schedule?->workShift;

        $gps = $this->gpsService->validate(
            $data['latitude'], $data['longitude'], 'check_in', null, $user->id
        );

        if (!$gps['is_valid']) {
            throw new \App\Exceptions\AttendanceException(
                "You are {$gps['distance_meters']}m from the nearest office. Must be within allowed radius."
            );
        }

        $photoPath   = null;
        if (!empty($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $photoPath = $data['photo']->store("attendance/checkin/{$user->id}/" . now()->format('Y/m'), 'public');
        }

        $lateMinutes = 0;
        $status      = 'present';

        if ($shift) {
            $shiftStart = Carbon::parse(now()->toDateString() . ' ' . $shift->start_time);
            $diff       = now()->diffInMinutes($shiftStart, false);

            if ($diff < 0 && abs($diff) > $shift->late_tolerance_minutes) {
                $lateMinutes = abs($diff);
                $status      = 'late';
            }
        }

        $attendance = Attendance::create([
            'user_id'                  => $user->id,
            'work_shift_id'            => $shift?->id,
            'attendance_date'          => now()->toDateString(),
            'check_in_time'            => now(),
            'check_in_latitude'        => $data['latitude'],
            'check_in_longitude'       => $data['longitude'],
            'check_in_photo'           => $photoPath,
            'check_in_gps_valid'       => true,
            'check_in_distance_meters' => $gps['distance_meters'],
            'status'                   => $status,
            'late_minutes'             => $lateMinutes,
            'notes'                    => $data['notes'] ?? null,
        ]);

        $user->gpsValidations()->latest()->first()?->update(['attendance_id' => $attendance->id]);

        return $attendance->fresh(['workShift']);
    }

    public function checkOut(User $user, array $data): Attendance
    {
        $attendance = Attendance::where('user_id', $user->id)
            ->where('attendance_date', now()->toDateString())
            ->first();

        if (!$attendance) {
            throw new \App\Exceptions\AttendanceException('No check-in record found for today.');
        }

        if ($attendance->isCheckedOut()) {
            throw new \App\Exceptions\AttendanceException('Already checked out today.');
        }

        $gps = $this->gpsService->validate(
            $data['latitude'], $data['longitude'], 'check_out', $attendance->id, $user->id
        );

        if (!$gps['is_valid']) {
            throw new \App\Exceptions\AttendanceException(
                "You are {$gps['distance_meters']}m from the nearest office."
            );
        }

        $photoPath = null;
        if (!empty($data['photo']) && $data['photo'] instanceof UploadedFile) {
            $photoPath = $data['photo']->store("attendance/checkout/{$user->id}/" . now()->format('Y/m'), 'public');
        }

        $workingMinutes = now()->diffInMinutes($attendance->check_in_time);

        $attendance->update([
            'check_out_time'      => now(),
            'check_out_latitude'  => $data['latitude'],
            'check_out_longitude' => $data['longitude'],
            'check_out_photo'     => $photoPath,
            'check_out_gps_valid' => true,
            'working_minutes'     => $workingMinutes,
        ]);

        return $attendance->fresh(['workShift']);
    }
}
