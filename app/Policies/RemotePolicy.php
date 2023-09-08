<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HasPermission;

class RemotePolicy
{
    use HandlesAuthorization, HasPermission;

    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr']) || $user->position == config('database.position.po');
    }


    public function view(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr', 'member']);
    }

    public function create(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr']);
    }

    public function update(User $user)
    {

        return $user->hasAnyRole(['admin',  'hr']) || $user->position == config('database.position.po');
    }

    public function delete(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr']);
    }
}
