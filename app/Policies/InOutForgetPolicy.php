<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HasPermission;
use App\Models\InOutForget;

class InOutForgetPolicy
{
    use HandlesAuthorization, HasPermission;

    public function before(User $user)
    {
        if ($user->hasRole('admin')) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr', 'po']);
    }

    public function view(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr', 'member']);
    }

    public function details(User $user, InOutForget $inOutForget)
    {
        $userId = $inOutForget->user_id;
        return $user->hasAnyRole(['admin', 'hr', 'po']) || $user->id === $userId;
    }

    public function update(User $user, InOutForget $inOutForget)
    {
        $userId = $inOutForget->user_id;
        return $user->id === $userId;
    }

    public function delete(User $user, InOutForget $inOutForget)
    {
        $userId = $inOutForget->user_id;
        return $user->id === $userId;
    }

    public function approve(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr', 'po']);
    }
}
