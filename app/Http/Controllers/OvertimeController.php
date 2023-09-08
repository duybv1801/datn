<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OvertimeRepository;
use Illuminate\Support\Carbon;
use Laracasts\Flash\Flash;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateOTRequest;
use App\Repositories\HolidayRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TeamRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Mail\ApproveOT;
use Illuminate\Support\Facades\Mail;

class OvertimeController extends Controller
{
    private $otRepository;
    private $holidayRepository;
    private $settingRepository;
    private $teamRepository;
    private $userRepository;
    private $statusData;

    public function __construct(
        OvertimeRepository $otRepo,
        HolidayRepository $holidayRepo,
        SettingRepository $settingRepo,
        TeamRepository $teamRepo,
        UserRepository $userRepo
    ) {
        $this->otRepository = $otRepo;
        $this->holidayRepository = $holidayRepo;
        $this->settingRepository = $settingRepo;
        $this->teamRepository = $teamRepo;
        $this->userRepository = $userRepo;
        $this->statusData = [
            config('define.overtime.registered') => ['label' => trans('overtime.registered'), 'class' => 'badge badge-primary'],
            config('define.overtime.approved') => ['label' => trans('overtime.approved'), 'class' => 'badge badge-success'],
            config('define.overtime.confirm') => ['label' => trans('overtime.confirm'), 'class' => 'badge badge-info'],
            config('define.overtime.confirmed') => ['label' => trans('overtime.confirmed'), 'class' => 'badge badge-secondary'],
            config('define.overtime.rejected') => ['label' => trans('overtime.rejected'), 'class' => 'badge badge-warning'],
            config('define.overtime.cancel') => ['label' => trans('overtime.cancel'), 'class' => 'badge badge-danger'],
        ];
    }

    public function index(Request $request)
    {
        $searchParams = [
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
        ];
        $allOvertimes = $this->otRepository->searchByConditions($searchParams);
        $overtimes = $this->otRepository->userQuery($allOvertimes, Auth::id());
        $overtimes->getCollection()->transform(function ($item) {
            $item->total_hours = round($item->total_hours / 60, 1);
            $item->salary_hours = round($item->salary_hours / 60, 1);
            $item->from_datetime = Carbon::parse($item->from_datetime);
            $item->to_datetime = Carbon::parse($item->to_datetime);
            $item->approver_id = $item->approver->code;
            return $item;
        });

        $statusData = $this->statusData;

        return view('overtime.index', compact('overtimes', 'statusData'));
    }

    public function manage(Request $request)
    {
        $searchParams = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];
        $userPosition = Auth::user()->position;
        $overtimesQuery = $this->otRepository->searchByConditions($searchParams);
        if ($userPosition == config('define.position.po')) {
            $overtimesQuery = $this->otRepository->poQuery($overtimesQuery, Auth::id());
        }

        $allOvertimes = $overtimesQuery->get();
        $overtimes = new Collection();
        foreach ($allOvertimes as $item) {
            $item->total_hours = round($item->total_hours / 60, 1);
            $item->salary_hours = round($item->salary_hours / 60, 1);
            $item->from_datetime = Carbon::parse($item->from_datetime);
            $item->to_datetime = Carbon::parse($item->to_datetime);
            $item->user_id = $item->user->code;
            if (!$request->filled('query') || strpos($item->user_id, $request->query('query')) !== false) {
                $overtimes->add($item);
            }
        }
        $statusData = $this->statusData;

        $perPage = config('define.paginate');
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $overtimes = new LengthAwarePaginator(
            $overtimes->forPage($currentPage, $perPage),
            $overtimes->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        return view('overtime.manage', compact('overtimes', 'statusData'));
    }

    public function create()
    {
        $userId = Auth::id();
        $teamInfo = $this->teamRepository->getTeamInfo($userId);
        return view('overtime.create', compact('teamInfo'));
    }

    public function store(CreateOTRequest $request)
    {
        $input['user_id'] = Auth::id();
        $input['reason'] = $request->reason;
        $input['approver_id'] = $request->approver_id;
        $input['comment'] = $request->comment;
        $input['status'] = config('define.overtime.registered');
        $avatar = $request->file('evident');
        $path = 'public/upload/' . date('Y/m/d');
        $filename = Str::random(10) . '.' . $avatar->extension();
        $imagePath = $avatar->storeAs($path, $filename);
        $imageUrl = asset(Storage::url($imagePath));
        $input['evident'] = $imageUrl;

        $holidays = $this->holidayRepository->all()->pluck('date')->transform(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();

        $start_at = Carbon::createFromFormat(config('define.datetime'), $request->input('from_datetime'));
        $end_at = Carbon::createFromFormat(config('define.datetime'), $request->input('to_datetime'));
        $input['total_hours'] = $start_at->diffInMinutes($end_at);
        $input['from_datetime'] = $start_at->format(config('define.datetime_db'));
        $input['to_datetime'] = $end_at->format(config('define.datetime_db'));

        $dateRanges = [
            [$input['from_datetime'], $input['to_datetime']]
        ];
        $result = $this->calculateDayNightMinutes($dateRanges, $holidays);
        $coefficients = $this->settingRepository->getCoefficients();

        $input['salary_hours'] = ($coefficients['day_time_ot'] * $result['dayMinutes']
            + $coefficients['night_time_ot'] * $result['nightMinutes']
            + $coefficients['ot_day_dayoff'] * $result['dayMinutesWeekend']
            + $coefficients['ot_night_dayoff'] * $result['nightMinutesWeekend']
            + $coefficients['ot_day_holiday'] * $result['dayMinutesHolidays']
            + $coefficients['ot_night_holiday'] * $result['nightMinutesHolidays']) / 100;

        $this->otRepository->create($input);
        return redirect()->route('overtimes.index')->with('success', trans('validation.crud.created'));
    }

    public function edit($id)
    {
        $overtime = $this->otRepository->find($id);
        $overtime->from_datetime = Carbon::parse($overtime->from_datetime);
        $overtime->to_datetime = Carbon::parse($overtime->to_datetime);
        $userId = Auth::id();
        $teamInfo = $this->teamRepository->getTeamInfo($userId);
        return view('overtime.edit', compact('overtime', 'teamInfo'));
    }

    public function approve($id)
    {
        $overtime = $this->otRepository->find($id);
        $overtime->from_datetime = Carbon::parse($overtime->from_datetime);
        $overtime->to_datetime = Carbon::parse($overtime->to_datetime);
        $userId = Auth::id();
        $teamInfo = $this->teamRepository->getTeamInfo($userId);
        return view('overtime.approve', compact('overtime', 'teamInfo'));
    }

    public function approveAction(Request $request, $id)
    {
        $overtime = $this->otRepository->find($id);
        $user = $this->userRepository->find($overtime->user_id);
        $email = $user->email;
        $input['status'] = $request->status;
        $input['comment'] = $request->comment;
        $this->otRepository->update($input, $id);
        Mail::to($email)->send(new ApproveOT($input));
        Flash::success(trans('validation.crud.updated'));
        return redirect()->route('overtimes.manage')->with('success', trans('validation.crud.created'));
    }

    public function update(Request $request, $id)
    {
        $overtime = $this->otRepository->find($id);
        $input['reason'] = $request->reason;
        $input['approver_id'] = $request->approver_id;
        $input['comment'] = $request->comment;
        $avatar = $request->file('evident');
        if ($avatar) {
            $path = 'public/upload/' . date('Y/m/d');
            $filename = Str::random(10) . '.' . $avatar->extension();
            $imagePath = $avatar->storeAs($path, $filename);
            $imageUrl = Storage::url($imagePath);
            $input['evident'] = $imageUrl;
            $old_imagePath = str_replace('/storage', 'public', $overtime->evident);
            Storage::delete($old_imagePath);
        }

        $holidays = $this->holidayRepository->all()->pluck('date')->transform(function ($date) {
            return $date->format('Y-m-d');
        })->toArray();

        $start_at = Carbon::createFromFormat(config('define.datetime'), $request->input('from_datetime'));
        $end_at = Carbon::createFromFormat(config('define.datetime'), $request->input('to_datetime'));
        $input['total_hours'] = $start_at->diffInMinutes($end_at);
        $input['from_datetime'] = $start_at->format(config('define.datetime_db'));
        $input['to_datetime'] = $end_at->format(config('define.datetime_db'));

        $dateRanges = [
            [$input['from_datetime'], $input['to_datetime']]
        ];
        $result = $this->calculateDayNightMinutes($dateRanges, $holidays);
        $coefficients = $this->settingRepository->getCoefficients();

        $input['salary_hours'] = ($coefficients['day_time_ot'] * $result['dayMinutes']
            + $coefficients['night_time_ot'] * $result['nightMinutes']
            + $coefficients['ot_day_dayoff'] * $result['dayMinutesWeekend']
            + $coefficients['ot_night_dayoff'] * $result['nightMinutesWeekend']
            + $coefficients['ot_day_holiday'] * $result['dayMinutesHolidays']
            + $coefficients['ot_night_holiday'] * $result['nightMinutesHolidays']) / 100;

        $this->otRepository->update($input, $id);
        Flash::success(trans('validation.crud.updated'));

        return redirect()->route('overtimes.index')->with('success', trans('validation.crud.created'));
    }

    public function cancel($id)
    {
        $overtime = $this->otRepository->find($id);
        $currentTime = now();
        $registrationTime = $overtime->from_datetime;
        $status = $overtime->status;
        if ($currentTime->greaterThanOrEqualTo($registrationTime) || $status != 1) {
            Flash::error(trans('Cannot cancel'));
            return redirect(route('overtimes.index'));
        }

        $overtime->status = config('define.overtime.cancel');
        $overtime->save();

        Flash::success(trans('validation.crud.cancel'));

        return redirect(route('overtimes.index'));
    }

    private function calculateDayNightMinutes($dateRanges, $holidays)
    {
        $dayMinutes = 0;
        $nightMinutes = 0;
        $dayMinutesWeekend = 0;
        $nightMinutesWeekend = 0;
        $dayMinutesHolidays = 0;
        $nightMinutesHolidays = 0;
        $nightTimeStart = $this->settingRepository->findByKey('ot_night_time_start')->value;
        list($nightStartHour, $nightStartMinutes) = explode(':', $nightTimeStart);
        $nightTimeEnd = $this->settingRepository->findByKey('ot_night_time_end')->value;
        list($nightEndHour, $nightEndMinutes) = explode(':', $nightTimeEnd);

        foreach ($dateRanges as $dateRange) {
            [$startDateTime, $endDateTime] = $dateRange;

            $start = Carbon::parse($startDateTime);
            $end = Carbon::parse($endDateTime);

            while ($start < $end) {
                $hour = $start->hour;

                if (($hour > $nightStartHour || ($hour == $nightStartHour && $start->minute >= $nightStartMinutes)) ||
                    ($hour < $nightEndHour || ($hour == $nightEndHour && $start->minute < $nightEndMinutes))
                ) {
                    if (in_array($start->format('Y-m-d'), $holidays)) {
                        $nightMinutesHolidays++;
                    } elseif ($start->dayOfWeek === 6 || $start->dayOfWeek === 0) {
                        $nightMinutesWeekend++;
                    } else {
                        $nightMinutes++;
                    }
                } else {
                    if (in_array($start->format('Y-m-d'), $holidays)) {
                        $dayMinutesHolidays++;
                    } elseif ($start->dayOfWeek === 6 || $start->dayOfWeek === 0) {
                        $dayMinutesWeekend++;
                    } else {
                        $dayMinutes++;
                    }
                }

                $start->addMinute();
            }
        }

        return [
            'dayMinutes' => $dayMinutes,
            'nightMinutes' => $nightMinutes,
            'dayMinutesWeekend' => $dayMinutesWeekend,
            'nightMinutesWeekend' => $nightMinutesWeekend,
            'dayMinutesHolidays' => $dayMinutesHolidays,
            'nightMinutesHolidays' => $nightMinutesHolidays,
        ];
    }
}
