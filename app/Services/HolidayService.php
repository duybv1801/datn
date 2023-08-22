<?php

namespace App\Services;

use App\Repositories\HolidayRepository;
use Illuminate\Http\UploadedFile;
use Carbon\Carbon;

class HolidayService
{
    protected $holidayRepository;

    public function __construct(HolidayRepository $holidayRepository)
    {
        $this->holidayRepository = $holidayRepository;
    }

    public function getAllHolidays()
    {
        return $this->holidayRepository->getHolidays();
    }

    public function store($data)
    {
        $title = $data->title;
        $selectOption = $data->select_option;

        if ($selectOption == 1) {
            $date = Carbon::createFromFormat('m/d/Y', $data->date);
            $this->holidayRepository->create([
                'date' => $date,
                'title' => $title,
            ]);
        } else {
            $dateRange = $data->daterange;
            list($startDate, $endDate) = explode(' - ', $dateRange);

            $startDateObj = Carbon::createFromFormat('m/d/Y', $startDate);
            $endDateObj = Carbon::createFromFormat('m/d/Y', $endDate);

            while ($startDateObj <= $endDateObj) {
                $this->holidayRepository->create([
                    'date' => $startDateObj,
                    'title' => $title,
                ]);
                $startDateObj->addDay();
            }
        }
    }

    public function import(UploadedFile $file, $year)
    {
        if ($this->holidayRepository->hasHolidaysByYear($year)) {
            return redirect()->route('holidays.index')->with('error', trans('holiday.import_holidays') . $year);
        }
        $contents = file_get_contents($file->path());
        $lines = explode("\n", $contents);

        array_shift($lines);

        foreach ($lines as $line) {
            $data = str_getcsv($line);
            $dateRange = $data[0];
            $title = $data[1];

            if (strpos($dateRange, '-')) {
                list($startDate, $endDate) = explode('-', $dateRange);

                $startDateParts = explode('/', trim($startDate));
                $endDateParts = explode('/', trim($endDate));

                $startDay = intval($startDateParts[0]);
                $startMonth = intval($startDateParts[1]);
                $endDay = intval($endDateParts[0]);
                $endMonth = intval($endDateParts[1]);

                for ($month = $startMonth; $month <= $endMonth; $month++) {
                    $firstDay = ($month === $startMonth) ? $startDay : 1;
                    $lastDay = ($month === $endMonth) ? $endDay : Carbon::parse("$year-$month-1")->daysInMonth;

                    for ($day = $firstDay; $day <= $lastDay; $day++) {
                        $date = $year . '-' . $month . '-' . $day;

                        $this->holidayRepository->create([
                            'date' => $date,
                            'title' => $title,
                        ]);
                    }
                }
            } else {
                $dateParts = explode('/', $dateRange);
                $date = $year . '-' . $dateParts[1] . '-' . $dateParts[0];

                $this->holidayRepository->create([
                    'date' => $date,
                    'title' => $title,
                ]);
            }
        }
    }

    public function destroy($id)
    {
        $this->holidayRepository->delete($id);
    }
}
