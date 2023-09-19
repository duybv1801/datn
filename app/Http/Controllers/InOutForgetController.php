<?php

namespace App\Http\Controllers;

use App\Models\InOutFoget;
use App\Repositories\TimesheetRepository;
use App\Repositories\TeamRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InOutForgetController extends Controller
{
    protected $timesheetRepository;
    protected $teamRepository;
    public function __construct(
        TimesheetRepository $timesheetRepository,
        TeamRepository $teamRepository
    ) {
        $this->teamRepository = $teamRepository;
        $this->timesheetRepository = $timesheetRepository;
    }
    public function create(Request $request)
    {
        $date = $request->get('date', date(config('define.date_show')));
        $date = Carbon::createFromFormat(config('define.date_show'), $date)->format(config('define.date_search'));
        $timesheet = $this->timesheetRepository->findByConditions(['record_date' => $date]);
        $data['timesheet'] = $timesheet;
        $inOutForget = new InOutFoget();
        $inOutForget->date = $date;
        if ($timesheet) {
            $inOutForget->in_time = $timesheet->in_time;
            $inOutForget->out_time = $timesheet->out_time;
        }
        $data['inOutForget'] = $inOutForget;
        $userId = Auth::id();
        $data['teamInfo'] = $this->teamRepository->getTeamInfo($userId);
        return view('in_out_forgets.create', $data);
    }
}
