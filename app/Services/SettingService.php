<?php

namespace App\Services;

use App\Repositories\SettingRepository;

class SettingService
{
    protected $settingRepository;

    public function __construct(SettingRepository $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }

    public function getAllSettings()
    {
        return $this->settingRepository->all();
    }

    public function updateSettings(array $data)
    {
        foreach ($data as $key => $value) {
            $setting = $this->settingRepository->findByKey($key);
            if ($setting) {
                $this->settingRepository->updateValue($setting, $value);
            }
        }
    }
}