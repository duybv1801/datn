<?php

namespace App\Http\Controllers;

use App\Repositories\HolidayRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TimesheetRepository;
use App\Repositories\UserRepository;
use App\Repositories\TeamRepository;
use App\Repositories\RemoteReponsitory;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Laracasts\Flash\Flash;

class HomeController extends Controller
{
    protected $timesheetRepository;
    protected $userRepository;
    protected $holidayRepository;
    protected $teamRepository;
    protected $settingRepository;
    protected $remoteRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        TimesheetRepository $timesheetRepository,
        HolidayRepository $holidayRepository,
        SettingRepository $settingRepository,
        UserRepository $userRepository,
        TeamRepository $teamRepository,
        RemoteReponsitory $remoteRepository
    ) {
        $this->middleware('auth');
        $this->timesheetRepository = $timesheetRepository;
        $this->holidayRepository = $holidayRepository;
        $this->settingRepository = $settingRepository;
        $this->userRepository = $userRepository;
        $this->teamRepository = $teamRepository;
        $this->remoteRepository = $remoteRepository;
    }

    public function index(Request $request)
    {
        $startDate = $request->start_date ?: Carbon::now()->subMonth()->startOfMonth()->format(config('define.date_show'));
        $endDate = $request->end_date ?: Carbon::now()->endOfMonth()->format(config('define.date_show'));
        $userId = Auth::id();
        $conditions = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'user_id' =>  $userId,
        ];
        $data = $conditions;
        $data['timesheetData'] = $this->timesheetRepository->searchByConditions($conditions);
        $data['timesheetData']->getCollection()->transform(function ($item) {
            $item->working_hours = round($item->working_hours / config('define.hour'), config('define.decimal'));
            $item->leave_hours = round($item->leave_hours / config('define.hour'), config('define.decimal'));
            $item->overtime_hours = round($item->overtime_hours / config('define.hour'), config('define.decimal'));
            $item->record_date = Carbon::parse($item->record_date)->format(config('define.date_show'));
            return $item;
        });
        $holiday = $this->holidayRepository->searchByConditions($conditions)->pluck('date');
        $countHoliday = 0;
        $setting = $this->settingRepository->searchByConditions(['key' => 'working_time'])->pluck('value', 'key')->toArray();
        $hourPerDay = (int)$setting['working_time'];
        foreach ($holiday as $date) {
            if (!$date->isWeekend()) {
                $countHoliday++;
            }
        }
        $date = now()->format(config('define.date_search'));
        $data['checkRemote']  = $this->remoteRepository->checkRemoteTime($userId, $date);
        $data['checkRemoteCheckIn'] = $data['checkRemote'] && $this->timesheetRepository->checkRemoteCheckIn($userId, $date);
        $data['workingHours'] = $this->timesheetRepository->getWorkingHours($conditions) + $countHoliday * $hourPerDay;
        $data['totalHours'] = $this->calTotalHours($startDate, $endDate);
        return view('home', $data);
    }

    public function manage(Request $request)
    {
        $startDate = $request->start_date ?: Carbon::now()->subMonth()->startOfMonth()->format(config('define.date_show'));
        $endDate = $request->end_date ?: Carbon::now()->endOfMonth()->format(config('define.date_show'));
        $conditions = [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        if ($request->user_ids) {
            $conditions['user_ids'] = $request->user_ids;
        }
        $data = $conditions;
        if (Auth::user()->hasRole('po')) {
            $member = $this->teamRepository->getMember(Auth::id());
            $data['timesheetData'] = $this->timesheetRepository->searchByConditions($conditions, $member['userIds']);
            $data['users'] = $member['userData'];
        } else {
            $data['timesheetData'] = $this->timesheetRepository->searchByConditions($conditions);
            $data['users'] = $this->userRepository->all([], null, null, ['id', 'name']);
        }
        $data['timesheetData']->getCollection()->transform(function ($item) {
            $item->working_hours = round($item->working_hours / config('define.hour'), config('define.decimal'));
            $item->leave_hours = round($item->leave_hours / config('define.hour'), config('define.decimal'));
            $item->overtime_hours = round($item->overtime_hours / config('define.hour'), config('define.decimal'));
            $item->record_date = Carbon::parse($item->record_date)->format(config('define.date_show'));
            return $item;
        });
        return view('timesheet', $data);
    }

    public function export(Request $request)
    {
        $startDate = $request->start_date ?: Carbon::now()->subMonth()->startOfMonth()->format(config('define.date_show'));
        $endDate = $request->end_date ?: Carbon::now()->endOfMonth()->format(config('define.date_show'));
        $conditions = [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
        if ($request->user_id) {
            $conditions['user_id'] = $request->user_id;
        }

        $timesheetData = $this->timesheetRepository->searchByConditions($conditions);
        if (empty($timesheetData)) {
            $sampleCsvPath = public_path('sample_timesheet.csv');
            return response()->download($sampleCsvPath, 'sample_timesheet.csv');
        }
        $csvData = [
            [
                trans('timesheet.user_code'),
                trans('timesheet.name'),
                trans('timesheet.date'),
                trans('timesheet.check_in'),
                trans('timesheet.check_out'),
                trans('timesheet.status'),
                trans('timesheet.work_time'),
                trans('timesheet.ot_time'),
                trans('timesheet.leave_time')
            ]
        ];

        foreach ($timesheetData as $timesheet) {
            $csvData[] = [
                $timesheet->user->code,
                $timesheet->user->name,
                $timesheet->record_date,
                $timesheet->in_time,
                $timesheet->out_time,
                __('define.timesheet.status.' . $timesheet->status),
                round($timesheet->working_hours / config('define.hour'), config('define.decimal')),
                round($timesheet->overtime_hours / config('define.hour'), config('define.decimal')),
                round($timesheet->leave_hours / config('define.hour'), config('define.decimal'))
            ];
        }

        $output = fopen('php://output', 'w');
        fputs($output, "\xEF\xBB\xBF");
        foreach ($csvData as $row) {
            fputcsv($output, $row, ',', '"');
        }
        fclose($output);
        $fileName = 'timesheet_' . date(config('define.date_show')) . '.csv';

        $headers = [
            "Content-type" => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename={$fileName}",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        return response('', 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => [
                'required',
                'file',
                'mimes:xlsx,xls',
            ],
        ]);

        $file = $request->file('csv_file');
        $spreadsheet = IOFactory::load($file);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray();
        $userCode = $this->userRepository->all([], null, null, ['code'])->pluck('code')->toArray();
        $groupedData = [];
        $importData = [];
        $userCodeToUserId = [];

        foreach ($userCode as $code) {
            $user = $this->userRepository->getUserByCode($code);
            if ($user) {
                $userCodeToUserId[$code] = $user->id;
            }
        }
        foreach ($rows as $row) {
            $userCodeKey = $row[config('define.import_data.code')];

            if (in_array($userCodeKey, $userCode)) {
                $carbonDate = Carbon::createFromFormat(config('define.datetime_ts'), $row[config('define.import_data.record_date')])
                    ->format(config('define.date_search'));

                $combinedKey = "$userCodeKey|$carbonDate";

                if (!isset($groupedData[$combinedKey])) {
                    $groupedData[$combinedKey] = [];
                }

                $groupedData[$combinedKey][] = $row;
            }
        }

        foreach ($groupedData as $combinedKey => $group) {
            $resultGroup = [];
            $earliestTime = null;
            $latestTime = null;

            list($userCode, $date) = explode('|', $combinedKey);
            $userId = $userCodeToUserId[$userCode] ?? null;

            $resultGroup[config('define.home.userId')] = $userId;
            $resultGroup[config('define.home.recordDate')] = $date;

            foreach ($group as $row) {
                $currentTime = strtotime($row[config('define.import_data.time')]);

                if ($earliestTime === null || $currentTime < $earliestTime) {
                    $earliestTime = $currentTime;
                }

                if ($latestTime === null || $currentTime > $latestTime) {
                    $latestTime = $currentTime;
                }
            }

            foreach ($group as $row) {
                $currentTime = strtotime($row[config('define.import_data.time')]);
                if ($currentTime == $earliestTime || $currentTime == $latestTime) {
                    if (empty($resultGroup[config('define.home.inTime')])) {
                        $resultGroup[config('define.home.inTime')] = $row[config('define.import_data.time')];
                    } else {
                        $resultGroup[config('define.home.outTime')] = $row[config('define.import_data.time')];
                    }
                }
            }
            $importData[] = $resultGroup;
        }
        $this->timesheetRepository->createTimesheet($importData);
        Flash::success(trans('validation.crud.imported'));

        return redirect()->route('timesheet.manage');
    }

    private function calTotalHours($startDate, $endDate)
    {
        $start = Carbon::createFromFormat(config('define.date_show'), $startDate);
        $end = Carbon::createFromFormat(config('define.date_show'), $endDate);
        $day = 0;
        while ($start->lte($end)) {
            if (!$start->isWeekend()) {
                $day++;
            }

            $start->addDay();
        }
        $setting = $this->settingRepository->searchByConditions(['key' => 'working_time'])->pluck('value', 'key')->toArray();
        $hourPerDay = (int)$setting['working_time'];
        return $day * $hourPerDay;
    }
}
