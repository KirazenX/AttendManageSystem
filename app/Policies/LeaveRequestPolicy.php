<?php
namespace App\Policies;

use App\Models\LeaveRequest;
use App\Models\User;

class LeaveRequestPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function approve(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'hr']) ||
               $user->hasPermissionTo('approve-leave');
    }
}