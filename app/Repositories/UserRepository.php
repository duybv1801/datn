<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\BaseRepository;

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
}
