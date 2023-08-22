<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Repositories\HolidayRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TimesheetRepository;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    protected $timesheetRepository;

    protected $holidayRepository;

    protected $settingRepository;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        TimesheetRepository $timesheetRepository,
        HolidayRepository $holidayRepository,
        SettingRepository $settingRepository
        )
    {
        $this->middleware('auth');
        $this->timesheetRepository = $timesheetRepository;
        $this->holidayRepository = $holidayRepository;
        $this->settingRepository = $settingRepository;
    }

    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $conditions = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'user_id' => Auth::user()->id
        ];
        $data = $conditions;
        
        $data['timesheetData'] = $this->timesheetRepository->searchByConditions($conditions);
        $data['workingHours'] = $this->timesheetRepository->getWorkingHours($conditions);
        $data['totalHours'] = $this->calTotalHours($startDate, $endDate) ;
       
        return view('home', $data);
    }

    private function calTotalHours($startDate, $endDate) {
        $conditions = [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];

        $holiday = $this->holidayRepository->searchByConditions($conditions)->pluck('date')->toArray();
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $day = 0;
        while($start->lte($end)) {
            if(!$start->isWeekend() && !in_array($start->format('Y-m-d'), $holiday)) {
                $day++;
            }

            $start->addDay();
        }
        $setting = $this->settingRepository->searchByConditions(['key' => 'max_working_minutes_everyday_day'])->pluck('value', 'key')->toArray();
        $hourPerDay = (int)$setting['max_working_minutes_everyday_day']/60;
        return $day * $hourPerDay;
    }
}