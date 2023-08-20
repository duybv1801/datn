<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStaffRequest extends FormRequest
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
        $id = $this->route('manager_staff');
        $rules = [
            'name'     => 'required|max:15',
            'email'    => 'required|email|unique:users,email,' . $id,
            'code'    => 'required|unique:users,code,' . $id,
            'password' => 'confirmed',
            'phone' => 'size:10|unique:users,phone,' . $id,
        ];

        return $rules;
    }
}
