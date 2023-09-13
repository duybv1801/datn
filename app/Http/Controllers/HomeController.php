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
    ) {
        $this->middleware('auth');
        $this->timesheetRepository = $timesheetRepository;
        $this->holidayRepository = $holidayRepository;
        $this->settingRepository = $settingRepository;
    }

    public function index(Request $request)
    {
        return view('home');
    }
}
