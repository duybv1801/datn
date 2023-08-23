<?php

namespace App\Repositories;

use App\Models\Remote;
use App\Repositories\BaseRepository;

/**
 * Class RemoteReponsitory
 * @package App\Repositories
 */

class RemoteReponsitory extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'user_id',
        'from_datetime',
        'to_datetime',
        'total_hours',
        'reason',
        'evident',
        'approver_id',
        'comment',
        'status',

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
        return Remote::class;
    }
}
