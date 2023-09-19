<?php

namespace App\Repositories;

use App\Models\Timesheet;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use App\Events\TimesheetUpdate;

/**
 * Class TimesheetRepository
 * @package App\Repositories
 */

class TimesheetRepository extends BaseRepository
{

    /**
     * @var array
     */
    protected $fieldSearchable = [
        'record_date',

    ];

    public function model()
    {
        return Timesheet::class;
    }
    /**
     * Get searchable fields array
     * @return array
     */
    public function getFieldsSearchable()
    {
        $this->fieldSearchable;
    }

    public function searchByConditions($search, $userIds = [])
    {
        $query = $this->model;
        if (count($search)) {
            foreach ($search as $key => $value) {
                switch ($key) {
                    case 'start_date':
                        $startDate = Carbon::createFromFormat(config('define.date_show'), $value)->format(config('define.date_search'));
                        $query = $query->where('record_date', '>=', $startDate);
                        break;
                    case 'end_date':
                        $endDate = Carbon::createFromFormat(config('define.date_show'), $value)->format(config('define.date_search'));
                        $query = $query->where('record_date', '<=', $endDate);
                        break;
                    default:
                        $query = $query->where($key, $value);
                        break;
                }
            }
        }
        if ($userIds != null) {
            $query = $query->whereIn('user_id', $userIds);
        }
        return $query->with('user')->orderBy('record_date', 'DESC')->paginate(config('define.paginate'));
    }

    public function getWorkingHours($search)
    {
        $query = $this->model;
        if (count($search)) {
            foreach ($search as $key => $value) {
                switch ($key) {
                    case 'start_date':
                        $startDate = Carbon::createFromFormat(config('define.date_show'), $value)->format(config('define.date_search'));
                        $query = $query->where('record_date', '>=', $startDate);
                        break;
                    case 'end_date':
                        $endDate = Carbon::createFromFormat(config('define.date_show'), $value)->format(config('define.date_search'));
                        $query = $query->where('record_date', '<=', $endDate);
                        break;
                    default:
                        $query = $query->where($key, $value);
                        break;
                }
            }
        }

        return $query->get()->sum(function ($timesheet) {
            return round($timesheet->working_hours / config('define.hour'), config('define.decimal'))
                + round($timesheet->leave_hours / config('define.hour'), config('define.decimal'))
                + round($timesheet->remote_hours / config('define.hour'), config('define.decimal'))
                + round($timesheet->overtime_hours / config('define.hour'), config('define.decimal'));
        });
    }

    public function findByConditions($search)
    {
        $query = $this->model;
        if (count($search)) {
            foreach ($search as $key => $value) {
                switch ($key) {
                    case 'start_date':
                        $query = $query->where('record_date', '>=', $value);
                        break;
                    case 'end_date':
                        $query = $query->where('record_date', '<=', $value);
                        break;
                    default:
                        $query = $query->where($key, $value);
                        break;
                }
            }
        }

        return $query->with('user')->orderBy('record_date', 'DESC')->first();
    }

    public function createTimesheet($importData)
    {
        $newTimesheets = [];
        $userIds = User::pluck('id')->toArray();

        foreach ($importData as $key => $data) {
            $userId = $data['MaID'];
            if (!in_array($userId, $userIds)) {
                unset($importData[$key]);
                $recordDate = $data['Ngay'];
                $newTimesheets[] = [
                    'userId' => $userId,
                    'recordDate' => $recordDate,
                ];
            }
        }
        $existingTimesheets = Timesheet::whereIn('user_id', array_column($newTimesheets, 'userId'))
            ->whereIn('record_date', array_column($newTimesheets, 'recordDate'))
            ->get();
        foreach ($importData as $data) {
            $userId = $data['MaID'];
            $recordDate = $data['Ngay'];
            $key = $userId . $recordDate;
            $existingTimesheet = $existingTimesheets->first(function ($item) use ($key) {
                return $item->user_id . $item->record_date === $key;
            });
            if ($existingTimesheet) {
                $updateData = [
                    'in_time' => $data['GioDen'],
                ];

                if (isset($data['GioVe'])) {
                    $updateData['out_time'] = $data['GioVe'];
                }
                $existingTimesheet->update($updateData);
                event(new TimesheetUpdate($existingTimesheet));
            } else {
                $createData = [
                    'user_id' => $data['MaID'],
                    'record_date' => $data['Ngay'],
                    'in_time' => $data['GioDen'],
                    'check_in' => $data['GioDen'],
                ];
                if (isset($data['GioVe']) && $data['GioVe'] != null) {
                    $createData['out_time'] = $data['GioVe'];
                    $createData['check_out'] = $data['GioVe'];
                }
                $timesheet = $this->model->create($createData);
                event(new TimesheetUpdate($timesheet));
            }
        }
    }

    public function updateOT($overtime)
    {
        $recordDate = Carbon::parse($overtime->to_datetime)->format(config('define.date_search'));
        $data['user_id'] = $overtime->user_id;
        $data['record_date'] = $recordDate;
        $data['overtime_hours'] = $overtime->salary_hours;
        $existingTimesheet = Timesheet::where('user_id', $data['user_id'])
            ->where('record_date', $data['record_date'])->first();
        if ($existingTimesheet) {
            $existingTimesheet->update($data);
        } else {
            $this->model->create($data);
        }
    }

    public function updateRemote($remote)
    {
        $recordDate = Carbon::parse($remote->to_datetime)->format(config('define.date_search'));
        $data['user_id'] = $remote->user_id;
        $data['record_date'] = $recordDate;
        $data['remote_hours'] = $remote->total_hours;
        $existingTimesheet = Timesheet::where('user_id', $data['user_id'])
            ->where('record_date', $data['record_date'])->first();
        if ($existingTimesheet) {
            $existingTimesheet->update($data);
        } else {
            $this->model->create($data);
        }
    }

    public function updateLeave($leave)
    {
        $recordDate = Carbon::parse($leave->to_datetime)->format(config('define.date_search'));
        $data['user_id'] = $leave->user_id;
        $data['record_date'] = $recordDate;
        $data['leave_hours'] = $leave->total_hours;
        $existingTimesheet = Timesheet::where('user_id', $data['user_id'])
            ->where('record_date', $data['record_date'])->first();
        if ($existingTimesheet) {
            $existingTimesheet->update($data);
        } else {
            $this->model->create($data);
        }
    }
}
