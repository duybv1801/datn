<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Remote;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Auth;

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
    public function findByUserId($userId)
    {
        return $this->model->where('user_id', $userId);
    }

    public function searchByConditionsRemote($search)
    {
        $query = $this->model->where('user_id', Auth::user()->id);

        $query = $this->applySearchConditions($query, $search);

        return $query->paginate(config('define.paginate'));
    }
    public function searchByConditions($search)
    {
        $query = $this->model->orderBy('status', 'ASC')->orderBy('created_at', 'DESC');

        $query = $this->applySearchConditions($query, $search);

        return $query->paginate(config('define.paginate'));
    }
    public function searchByConditionPO($search)
    {
        $query = $this->model->where('approver_id', Auth::user()->id);

        $query = $this->applySearchConditions($query, $search);

        return $query->paginate(config('define.paginate'));
    }

    private function applySearchConditions($query, $search)
    {
        $startDate = isset($search['start_date']) ? Carbon::createFromFormat(config('define.date_show'), $search['start_date'])->format(config('define.datetime_db')) : now()->startOfYear()->format(config('define.date_search'));
        $endDate = isset($search['end_date']) ? Carbon::createFromFormat(config('define.date_show'), $search['end_date'])->format(config('define.datetime_db')) : now()->endOfYear()->format(config('define.date_search'));

        $query->orderBy('status', 'ASC')->orderBy('created_at', 'DESC')
            ->where('from_datetime', '>=', $startDate)
            ->where('to_datetime', '<=', $endDate);

        if (isset($search['query'])) {
            $query->whereHas('user', function ($subQuery) use ($search) {
                $subQuery->where('code', 'like', '%' . $search['query'] . '%');
            });
        }

        return $query;
    }
}
