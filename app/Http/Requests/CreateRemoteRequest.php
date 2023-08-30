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
            $fromDatetime = Carbon::createFromFormat('d/m/Y H:i', $this->input('from_datetime'));
            $toDatetime = Carbon::createFromFormat('d/m/Y H:i', $this->input('to_datetime'));


            $startSetting = Setting::where('key', 'check_in_time')->value('value');
            $endSetting = Setting::where('key', 'check_out_time')->value('value');

            $startTime = Carbon::createFromFormat('H:i', $startSetting);
            $endTime = Carbon::createFromFormat('H:i', $endSetting);

            $breakStartTimeSetting = Setting::where('key', 'lunch_time_start')->value('value');
            $breakEndTimeSetting = Setting::where('key', 'lunch_time_end')->value('value');

            $breakStartTime = Carbon::createFromFormat('H:i', $breakStartTimeSetting);
            $breakEndTime = Carbon::createFromFormat('H:i', $breakEndTimeSetting);

            if ($fromDatetime->format('H:i') < $startTime->format('H:i') || $fromDatetime->format('H:i') > $endTime->format('H:i')) {
                $validator->errors()->add('from_datetime', 'Thời gian bắt đầu không hợp lệ.');
            }

            if ($toDatetime->format('H:i') < $startTime->format('H:i') || $toDatetime->format('H:i') > $endTime->format('H:i')) {
                $validator->errors()->add('to_datetime', 'Thời gian kết thúc không hợp lệ.');
            }

            $totalDuration = $toDatetime->diffInHours($fromDatetime);

            if ($toDatetime->format('H:i') > $breakStartTime->format('H:i') && $fromDatetime->format('H:i') < $breakEndTime->format('H:i')) {
                $totalDuration -= $breakEndTime->diffInHours($breakStartTime);
            }

            if ($totalDuration > 8) {
                $validator->errors()->add('to_datetime', 'Thời gian làm việc không được vượt quá 8 giờ trong một ngày.');
            }

            if (!$validator->errors()->any()) {
                $this->merge([
                    'total_hours' => $totalDuration,
                    'from_datetime' => $fromDatetime->format('Y/m/d H:i'),
                    'to_datetime' => $toDatetime->format('Y/m/d H:i')
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
