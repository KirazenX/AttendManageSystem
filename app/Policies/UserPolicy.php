<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool  { return $user->hasAnyRole(['admin', 'hr']); }
    public function view(User $auth, User $target): bool { return $auth->hasAnyRole(['admin', 'hr']) || $auth->id === $target->id; }
    public function create(User $user): bool   { return $user->hasRole('admin'); }
    public function update(User $auth, User $target): bool { return $auth->hasRole('admin') || $auth->id === $target->id; }
    public function delete(User $auth, User $target): bool { return $auth->hasRole('admin') && $auth->id !== $target->id; }
}
