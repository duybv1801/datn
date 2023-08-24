<?php

namespace App\Repositories;

use App\Models\Holiday;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;

/**
 * Class HolidayRepository
 * @package App\Repositories
 */

class HolidayRepository extends BaseRepository
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

    public function getHolidays()
    {
        return Holiday::orderBy('date', 'asc')->paginate(10);
    }
    /**
     * Configure the Model
     **/
    public function model()
    {
        return Holiday::class;
    }


    public function searchByConditions($search)
    {
        $query = $this->model;

        if (!isset($search['start_date'])) {
            $start_date = now()->startOfMonth()->format('Y-m-d');
        } else {
            $start_date = Carbon::createFromFormat('d/m/Y', $search['start_date'])->format('Y-m-d');
        }

        if (!isset($search['end_date'])) {
            $end_date = now()->endOfMonth()->format('Y-m-d');
        } else {
            $end_date = Carbon::createFromFormat('d/m/Y', $search['end_date'])->format('Y-m-d');
        }

        if (isset($search['query'])) {
            $query = $query->where('title', 'like', '%' . $search['query'] . '%');
        }

        if (isset($search['sort_by']) && in_array($search['sort_by'], ['asc', 'desc'])) {
            $sortField = isset($search['order_by']) ? $search['order_by'] : 'date';
            $query = $query->orderBy($sortField, $search['sort_by']);
        }
        $query = $query->where('date', '>=', $start_date)->where('date', '<=', $end_date);

        return $query->paginate(10);
    }

    public function createHoliday($data)
    {
        $date = $data['date'];
        $title = $data['title'];
        $existingHoliday = $this->model->where('date', $date)->first();
        if ($existingHoliday) {
            $existingHoliday->update([
                'title' => $title,
            ]);
        } else {
            $this->model->create([
                'date' => $date,
                'title' => $title,
            ]);
        }
    }
}
