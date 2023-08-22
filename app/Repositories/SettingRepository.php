<?php

namespace App\Repositories;

use App\Models\Setting;
use App\Repositories\BaseRepository;

/**
 * Class SettingRepository
 * @package App\Repositories
 */

class SettingRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Setting::class;
    }


    public function searchByConditions($search)
    {
        $query = $this->model;
        if (count($search)) {
            foreach ($search as $key => $value) {
                switch ($key) {
                    default:
                        $query = $query->where($key, $value);
                        break;
                }
            }
        }

        return $query->get();
    }

    public function findByKey($key)
    {
        return Setting::where('key', $key)->first();
    }

    public function updateValue(Setting $setting, $value)
    {
        $setting->value = $value;
        $setting->save();
    }
}
