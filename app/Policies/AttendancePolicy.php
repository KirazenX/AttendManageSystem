<?php
namespace App\Policies;

use App\Models\Attendance;
use App\Models\User;

class AttendancePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'hr']);
    }

    public function view(User $authUser, Attendance $attendance): bool
    {
        return $authUser->hasAnyRole(['admin', 'hr']) || $authUser->id === $attendance->user_id;
    }
}