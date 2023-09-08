<?php

namespace App\Http\Requests;

use App\Models\Setting;
use Illuminate\Contracts\Validation\Validator;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class CreateRemoteRequest extends FormRequest
{
    public $attributes = [
        'total_hours' => null,
    ];

    public function authorize()
    {
        return true;
    }

    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator) {
            $fromDatetime = Carbon::createFromFormat(config('define.datetime'), $this->input('from_datetime'));
            $toDatetime = Carbon::createFromFormat(config('define.datetime'), $this->input('to_datetime'));

            $settings = Setting::whereIn('key', ['check_in_time', 'check_out_time', 'lunch_time_start', 'lunch_time_end', 'working_time'])->pluck('value', 'key');
            $startTime = Carbon::createFromFormat(config('define.time'), $settings['check_in_time']);
            $endTime = Carbon::createFromFormat(config('define.time'), $settings['check_out_time']);
            $breakStartTime = Carbon::createFromFormat(config('define.time'), $settings['lunch_time_start']);
            $breakEndTime = Carbon::createFromFormat(config('define.time'), $settings['lunch_time_end']);

            if ($fromDatetime->format(config('define.time')) < $startTime->format(config('define.time')) || $fromDatetime->format(config('define.time')) > $endTime->format(config('define.time'))) {
                $validator->errors()->add('from_datetime', trans('validation.crud.beggintime_false'));
            }

            if ($toDatetime->format(config('define.time')) < $startTime->format(config('define.time')) || $toDatetime->format(config('define.time')) > $endTime->format(config('define.time'))) {
                $validator->errors()->add('to_datetime', trans('validation.crud.endtime_false'));
            }

            $totalDuration = $toDatetime->diffInHours($fromDatetime);

            if ($toDatetime->format(config('define.time')) > $breakStartTime->format(config('define.time')) && $fromDatetime->format(config('define.time')) < $breakEndTime->format(config('define.time'))) {
                $totalDuration -= $breakEndTime->diffInHours($breakStartTime);
            }

            if ($totalDuration > $settings['working_time']) {
                $validator->errors()->add('to_datetime', trans('validation.crud.overtime_false'));
            }

            if (!$validator->errors()->any()) {
                $this->merge([
                    'total_hours' => $totalDuration,
                    'from_datetime' => $fromDatetime->format(config('define.datetime_db')),
                    'to_datetime' => $toDatetime->format(config('define.datetime_db'))
                ]);
            }
        });
    }
    public function rules()
    {

        return [
            'reason' => 'required',
            'from_datetime' => 'required|date_format:' . config('define.datetime'),
            'to_datetime' => 'required|date_format:' . config('define.datetime'),
        ];
    }
}
