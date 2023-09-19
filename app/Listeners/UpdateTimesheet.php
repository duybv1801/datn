<?php

namespace App\Listeners;

use App\Events\TimesheetUpdate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\Log;

class UpdateTimesheet implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    protected $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\TimesheetUpdate  $event
     * @return void
     */
    public function handle(TimesheetUpdate $event)
    {
        Log::info('Listener has completed processing.');
        $timesheet = $event->timesheet;
        if ($timesheet->in_time != null && $timesheet->out_time != null) {
            $checkIn = Carbon::parse($timesheet->in_time);
            $checkOut = Carbon::parse($timesheet->out_time);
            $settings = $this->settingRepository->getTimeLunch();
            $breakStartTime = Carbon::createFromFormat(config('define.time'), $settings['lunch_time_start']);
            $breakEndTime = Carbon::createFromFormat(config('define.time'), $settings['lunch_time_end']);
            $totalDuration = $checkOut->diffInMinutes($checkIn);

            if ($checkOut->gt($breakEndTime) && $checkIn->lt($breakStartTime)) {
                $totalDuration -= $breakEndTime->diffInMinutes($breakStartTime);
            }
            if (($checkIn->lt($breakEndTime) && $checkIn->gt($breakStartTime)) || ($checkOut->lt($breakEndTime) && $checkOut->gt($breakStartTime))) {
                $overlapStart = $checkIn->max($breakStartTime);
                $overlapEnd = $checkOut->min($breakEndTime);
                $overlapDuration = $overlapEnd->diffInMinutes($overlapStart);
                $totalDuration -= $overlapDuration;
            }
            $data['working_hours'] = $totalDuration;
            if ($totalDuration + $timesheet->leave_hours > $settings['working_time'] * config('define.hour')) {
                $data['status'] = config('define.timesheet.normal');
            }
            $timesheet->update($data);
        }
    }
}
