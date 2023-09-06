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

            $startSetting = Setting::where('key', 'check_in_time')->value('value');
            $endSetting = Setting::where('key', 'check_out_time')->value('value');

            $startTime = Carbon::createFromFormat(config('define.time'), $startSetting);
            $endTime = Carbon::createFromFormat(config('define.time'), $endSetting);

            $breakStartTimeSetting = Setting::where('key', 'lunch_time_start')->value('value');
            $breakEndTimeSetting = Setting::where('key', 'lunch_time_end')->value('value');

            $breakStartTime = Carbon::createFromFormat(config('define.time'), $breakStartTimeSetting);
            $breakEndTime = Carbon::createFromFormat(config('define.time'), $breakEndTimeSetting);

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

            if ($totalDuration > 8) {
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
            'from_datetime' => 'required|date_format:d/m/Y H:i',
            'to_datetime' => 'required|date_format:d/m/Y H:i|after:from_datetime',
        ];
    }
}
