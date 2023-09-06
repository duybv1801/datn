<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Role;
use App\Repositories\BaseRepository;
use Carbon\Carbon;

/**
 * Class UserRepository
 * @package App\Repositories
 */

class UserRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'code',
        'start_date',
        'official_start_date',
        'dependent_person',
        'gender',
        'contract',
        'birthday',
        'phone',
        'status',
        'position',
        'user_id',
        'avatar',
        'role_id',
        'team_id',
        'leave_hours_left',
        'leave_hours_left_in_month'

    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return User::class;
    }

    public function searchByConditions($search)
    {
        $query = $this->model;
        if (isset($search['query'])) {
            $query = $query->where('code', 'like', '%' . $search['query'] . '%');
        }
        $query = $query->orderBy('created_at', 'DESC');
        return $query;
    }
    public function getUsersByPosition($position)
    {
        return $this->model->where('position', $position)->get();
    }

    public function getRoleById($roleId)
    {
        return Role::where('id', $roleId)->first();
    }
}
