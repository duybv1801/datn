<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Overtime;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Traits\HasPermission;
use Illuminate\Support\Facades\Auth;


class OvertimePolicy
{
    use HandlesAuthorization, HasPermission;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function viewAny(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr']) || $user->position == config('define.position.po');
    }

    public function view(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr', 'member']);
    }

    public function update(User $user, $id)
    {
        $user_id = Overtime::find($id)->user_id;
        return $user->id === $user_id;
    }

    public function delete(User $user, $id)
    {
        $user_id = Overtime::find($id)->user_id;
        return $user->id === $user_id;
    }

    public function approve(User $user)
    {
        return $user->hasAnyRole(['admin', 'hr']) || $user->position == config('define.position.po');
    }
}
