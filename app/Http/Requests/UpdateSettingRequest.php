<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'check_in_time' => 'required|date_format:H:i',
            'check_out_time' => 'required|date_format:H:i|after:check_in_time',
            'flexible_time' => 'required|numeric|lt:working_time',
            'working_time' => 'required|numeric',
            'lunch_time_start' => 'required|date_format:H:i',
            'lunch_time_end' => 'required|date_format:H:i|after:lunch_time_start',
            'female_leave' => 'required|numeric',
            'paid_leave' => 'required|numeric',
            'remote' => 'required|numeric',
            'fresher_remote' => 'required|numeric',
            'ot_night_time_start' => 'required|date_format:H:i|after:19:59',
            'ot_night_time_end' => 'required|date_format:H:i|before:6:30',
            'day_time_ot' => 'required|numeric',
            'night_time_ot' => 'required|numeric',
            'ot_day_dayoff' => 'required|numeric',
            'ot_night_dayoff' => 'required|numeric',
            'ot_day_holiday' => 'required|numeric',
            'ot_night_holiday' => 'required|numeric',
            'max_working_minutes_everyday_day' => 'required|numeric|gt:working_time',
        ];
    }

    public function attributes()
    {
        return [];
    }
}