<?php

namespace App\Repositories;

use App\Models\Overtime;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;

/**
 * Class OvertimeRepository
 * @package App\Repositories
 * @version August 30, 2023, 3:07 am UTC
 */

class OvertimeRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [];

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
        return Overtime::class;
    }

    public function searchByConditions($search)
    {
        $query = $this->model;

        if (!isset($search['start_date'])) {
            $start_date = now()->startOfMonth();
        } else {
            $start_date = Carbon::createFromFormat(config('define.date_show'), $search['start_date'])->format(config('define.date_search'));
        }
        if (!isset($search['end_date'])) {
            $end_date = now()->endOfMonth();
        } else {
            $end_date = Carbon::createFromFormat(config('define.date_show'), $search['end_date'])->format(config('define.date_search'));
        }

        $query = $query->orderBy('status', 'ASC')->orderBy('created_at', 'DESC');
        $query = $query->where('from_datetime', '>=', $start_date)->where('to_datetime', '<=', $end_date);
        $query = $query->with('approver:id,code');

        return $query;
    }
}
