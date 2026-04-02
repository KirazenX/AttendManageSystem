<?php

namespace App\Notifications;

use App\Models\Attendance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class AttendanceLateAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public readonly Attendance $attendance) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'          => 'attendance_late',
            'attendance_id' => $this->attendance->id,
            'date'          => $this->attendance->attendance_date->format('Y-m-d'),
            'check_in_time' => $this->attendance->check_in_time?->format('H:i'),
            'late_minutes'  => $this->attendance->late_minutes,
            'message'       => "You were {$this->attendance->late_minutes} minutes late today.",
        ];
    }
}
