<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HasPermission;

class ManagerStaffPolicy
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
        return $user->hasRole('admin') || $user->hasRole('accounter') || $user->hasRole('hr');
    }

    public function view(User $user)
    {
        return $user->hasRole('admin') || $user->hasRole('accounter') || $user->hasRole('hr');
    }

    public function create(User $user)
    {
        return $user->hasRole('admin');
    }

    public function update(User $user)
    {

        return $user->hasRole('admin') || $user->hasRole('hr');
    }

    public function delete(User $user)
    {
        return $user->hasRole('admin');
    }
}
