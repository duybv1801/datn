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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class OvertimeController extends Controller
{
    private $otRepository;
    private $holidayRepository;
    private $settingRepository;

    public function __construct(
        OvertimeRepository $otRepo,
        HolidayRepository $holidayRepo,
        SettingRepository $settingRepo
    ) {
        $this->otRepository = $otRepo;
        $this->holidayRepository = $holidayRepo;
        $this->settingRepository = $settingRepo;
    }

    public function index(Request $request)
    {
        $searchParams = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];
        $overtimes = $this->otRepository->searchByConditions($searchParams)
            ->where('user_id', Auth::id())->paginate(config('define.paginate'));

        $overtimes->getCollection()->transform(function ($item) {
            $item->total_hours = round($item->total_hours / 60, 1);
            $item->salary_hours = round($item->salary_hours / 60, 1);
            $item->from_datetime = Carbon::parse($item->from_datetime);
            $item->to_datetime = Carbon::parse($item->to_datetime);
            $item->approver_id = $item->approver->code;
            return $item;
        });

        $statusData = [
            1 => ['label' => trans('overtime.registered'), 'class' => 'badge badge-primary'],
            2 => ['label' => trans('overtime.approved'), 'class' => 'badge badge-success'],
            3 => ['label' => trans('overtime.confirm'), 'class' => 'badge badge-info'],
            4 => ['label' => trans('overtime.confirmed'), 'class' => 'badge badge-secondary'],
            5 => ['label' => trans('overtime.rejected'), 'class' => 'badge badge-warning'],
            6 => ['label' => trans('overtime.cancel'), 'class' => 'badge badge-danger'],
        ];

        return view('overtime.index', compact('overtimes', 'statusData'));
    }

    public function manage(Request $request)
    {
        $searchParams = [
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ];
        $overtimes = $this->otRepository->searchByConditions($searchParams)
            ->paginate(config('define.paginate'));

        $overtimes->getCollection()->transform(function ($item) {
            $item->total_hours = round($item->total_hours / 60, 1);
            $item->salary_hours = round($item->salary_hours / 60, 1);
            $item->from_datetime = Carbon::parse($item->from_datetime);
            $item->to_datetime = Carbon::parse($item->to_datetime);
            $item->approver_id = $item->approver->code;
            return $item;
        });

        $statusData = [
            1 => ['label' => trans('overtime.registered'), 'class' => 'badge badge-primary'],
            2 => ['label' => trans('overtime.approved'), 'class' => 'badge badge-success'],
            3 => ['label' => trans('overtime.confirm'), 'class' => 'badge badge-info'],
            4 => ['label' => trans('overtime.confirmed'), 'class' => 'badge badge-secondary'],
            5 => ['label' => trans('overtime.rejected'), 'class' => 'badge badge-warning'],
            6 => ['label' => trans('overtime.cancel'), 'class' => 'badge badge-danger'],
        ];

        return view('overtime.manage', compact('overtimes', 'statusData'));
    }

    public function create()
    {
        return view('overtime.create');
    }

    public function store(CreateOTRequest $request)
    {
        $input['user_id'] = Auth::id();
        $input['reason'] = $request->reason;
        $input['approver_id'] = $request->approver_id;
        $input['comment'] = $request->comment;
        $input['status'] = 1;
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
        $dayCoefficient = $this->settingRepository->findByKey('day_time_ot')->value;
        $nightCoefficient = $this->settingRepository->findByKey('night_time_ot')->value;
        $dayCoefficientWeekend = $this->settingRepository->findByKey('ot_day_dayoff')->value;
        $nightCoefficientWeekend = $this->settingRepository->findByKey('ot_night_dayoff')->value;
        $dayCoefficientHoliday = $this->settingRepository->findByKey('ot_day_holiday')->value;
        $nightCoefficientHoliday = $this->settingRepository->findByKey('ot_night_holiday')->value;
        $input['salary_hours'] = ($dayCoefficient * $result['dayMinutes'] + $nightCoefficient * $result['nightMinutes']
            + $dayCoefficientWeekend * $result['dayMinutesWeekend'] + $nightCoefficientWeekend * $result['nightMinutesWeekend']
            + $dayCoefficientHoliday * $result['dayMinutesHolidays'] + $nightCoefficientHoliday * $result['nightMinutesHolidays']) / 100;

        $this->otRepository->create($input);
        return redirect()->route('overtimes.index')->with('success', trans('validation.crud.created'));
    }

    public function edit($id)
    {
        $overtime = $this->otRepository->find($id);
        $overtime->from_datetime = Carbon::parse($overtime->from_datetime);
        $overtime->to_datetime = Carbon::parse($overtime->to_datetime);

        return view('overtime.edit', compact('overtime'));
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
        $dayCoefficient = $this->settingRepository->findByKey('day_time_ot')->value;
        $nightCoefficient = $this->settingRepository->findByKey('night_time_ot')->value;
        $dayCoefficientWeekend = $this->settingRepository->findByKey('ot_day_dayoff')->value;
        $nightCoefficientWeekend = $this->settingRepository->findByKey('ot_night_dayoff')->value;
        $dayCoefficientHoliday = $this->settingRepository->findByKey('ot_day_holiday')->value;
        $nightCoefficientHoliday = $this->settingRepository->findByKey('ot_night_holiday')->value;
        $input['salary_hours'] = ($dayCoefficient * $result['dayMinutes'] + $nightCoefficient * $result['nightMinutes']
            + $dayCoefficientWeekend * $result['dayMinutesWeekend'] + $nightCoefficientWeekend * $result['nightMinutesWeekend']
            + $dayCoefficientHoliday * $result['dayMinutesHolidays'] + $nightCoefficientHoliday * $result['nightMinutesHolidays']) / 100;

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

        $overtime->status = 6;
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
