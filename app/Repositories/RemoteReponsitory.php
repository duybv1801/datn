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
        return Remote::where('user_id', $userId);
    }

    public function searchByConditionsRemote($search)
    {
        $query = $this->model;

        if (!isset($search['start_date'])) {
            $startDate = now()->startOfYear()->format(config('define.date_search'));
        } else {
            $startDate = Carbon::createFromFormat(config('define.date_show'), $search['start_date'])->format(config('define.datetime_db'));
        }
        if (!isset($search['end_date'])) {
            $endDate = now()->endOfYear()->format(config('define.date_search'));
        } else {
            $endDate = Carbon::createFromFormat(config('define.date_show'), $search['end_date'])->format(config('define.datetime_db'));
        }

        if (isset($search['query'])) {
            $query = $query->whereHas('user', function ($subQuery) use ($search) {
                $subQuery->where('code', 'like', '%' . $search['query'] . '%');
            });
        }
        $query = $query->where('user_id', Auth::user()->id);
        $query = $query->orderBy('status', 'ASC')->orderBy('created_at', 'DESC');
        $query = $query->where('from_datetime', '>=', $startDate)->where('to_datetime', '<=', $endDate);

        return $query;
    }
    public function searchByConditions($search)
    {
        $query = $this->model;

        if (!isset($search['start_date'])) {
            $startDate = now()->startOfYear()->format(config('define.date_search'));
        } else {
            $startDate = Carbon::createFromFormat(config('define.date_show'), $search['start_date'])->format(config('define.datetime_db'));
        }

        if (!isset($search['end_date'])) {
            $endDate = now()->endOfYear()->format(config('define.date_search'));
        } else {
            $endDate = Carbon::createFromFormat(config('define.date_show'), $search['end_date'])->format(config('define.datetime_db'));
        }

        if (isset($search['query'])) {
            $query = $query->whereHas('user', function ($subQuery) use ($search) {
                $subQuery->where('code', 'like', '%' . $search['query'] . '%');
            });
        }
        $query = $query->orderBy('status', 'ASC')->orderBy('created_at', 'DESC');
        $query = $query->where('from_datetime', '>=', $startDate)->where('to_datetime', '<=', $endDate);

        return $query;
    }
    public function searchByConditionsPO($search)
    {
        $query = $this->model;

        if (!isset($search['start_date'])) {
            $startDate = now()->startOfYear()->format(config('define.date_search'));
        } else {
            $startDate = Carbon::createFromFormat(config('define.date_show'), $search['start_date'])->format(config('define.datetime_db'));
        }

        if (!isset($search['end_date'])) {
            $endDate = now()->endOfYear()->format(config('define.date_search'));
        } else {
            $endDate = Carbon::createFromFormat(config('define.date_show'), $search['end_date'])->format(config('define.datetime_db'));
        }

        if (isset($search['query'])) {
            $query = $query->whereHas('user', function ($subQuery) use ($search) {
                $subQuery->where('code', 'like', '%' . $search['query'] . '%');
            });
        }
        $query = $query->where('approver_id', Auth::user()->id);
        $query = $query->orderBy('status', 'ASC')->orderBy('created_at', 'DESC');
        $query = $query->where('from_datetime', '>=', $startDate)->where('to_datetime', '<=', $endDate);
        return $query;
    }
}
