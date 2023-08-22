<?php

namespace App\Http\Controllers;

use App\Services\SettingService;
use App\Http\Requests\UpdateSettingRequest;
use Illuminate\Http\Request;


class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    public function index(Request $request)
    {
        if (!$request->user()->hasPermission('read')) {
            return redirect()->back();
        }
        $settings = $this->settingService->getAllSettings();
        return view('setting.setting', compact('settings'));
    }

    public function update(UpdateSettingRequest $request)
    {
        if (!$request->user()->hasPermission('update')) {
            return redirect()->back();
        }
        $data = $request->except('_token');
        $this->settingService->updateSettings($data);

<<<<<<< HEAD
        return redirect()->route('settings.index')->with('success', trans('validation.crud.updated'));
=======
        return redirect()->route('settings.index');
>>>>>>> 2f483590b841b591e0eb9ecc64e6d81d2bb1f1b9
    }
}
