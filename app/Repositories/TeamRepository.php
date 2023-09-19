<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Models\Team;

class TeamRepository extends BaseRepository
{

    protected $fieldSearchable = [
        'id',
        'name',
        'manager_id'
    ];
    protected $user;
    protected $team;

    public function __construct(User $user, Team $team)
    {
        $this->user = $user;
        $this->team = $team;
    }

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    public function model()
    {
        return Team::class;
    }

    public function getTeamInfo($userId)
    {
        $user = $this->user->find($userId);
        $manager = null;
        $teamIds = $user->teams->pluck('id')->toArray();
        $managers = [];
        if ($user->hasRole('po')) {
            $adminUsers = $this->user->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->select('id', 'code', 'email')->get();
            $managers = $adminUsers->toArray();
        } else {
            foreach ($teamIds as $teamId) {
                $team = $this->team->find($teamId);
                $members = $team->users()->select('users.id', 'users.code', 'users.email')->get();
                $managerId = $team->manager_id;
                $manager = $members->where('id', $managerId)->first();
                array_push($managers, $manager);
            }
        }
        $otherUsers = $this->user->select('id', 'code', 'email')
            ->where('id', '!=', $userId)
            ->get();
        $otherUsers = $otherUsers->toArray();
        $managerIds = array_column(array_filter($managers), 'id');
        $otherUsers = array_filter($otherUsers, function ($otherUser) use ($managerIds) {
            return !in_array($otherUser['id'], $managerIds);
        });

        return [
            'managers' => $managers,
            'otherUsers' => $otherUsers,
        ];
    }

    public function getMember($poId)
    {
        $team = Team::where('manager_id', $poId)->with('users')->first();
        $userIds = $team->users->pluck('id')->toArray();
        $userData = $team->users;
        return [
            'userIds' => $userIds,
            'userData' => $userData
        ];
    }

    public function getTeam()
    {
        return $this->team->pluck('manager');
    }
    public function getTeamList()
    {
        return $this->team->pluck('name', 'id');
    }
    public function findTeamById($id)
    {
        return Team::where('id', $id)->first();
    }
}
