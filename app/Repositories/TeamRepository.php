<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Remote;
use App\Models\Team;
use App\Repositories\BaseRepository;

/**
 * Class RemoteReponsitory
 * @package App\Repositories
 */

class TeamRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'manager'

    ];

    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Team::class;
    }

    public function getTeam()
    {
        return $this->model->distinct()->get(['manager']);
    }
}
