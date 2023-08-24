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

    public function getAllHolidays($request)
    {
        $searchParams = [
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'sort_by' => $request->input('sort_by', 'asc'),
            'order_by' => $request->input('order_by', 'date'),
            'query' => $request->input('query'),
        ];

        return $this->holidayRepository->searchByConditions($searchParams);
    }

    public function getHoliday($id)
    {
        return $this->holidayRepository->find($id);
    }

    public function store($data)
    {
        $title = $data->title;
        $dateRange = $data->daterange;
        list($startDate, $endDate) = explode(' - ', $dateRange);

        $startDateObj = Carbon::createFromFormat('m/d/Y', $startDate);
        $endDateObj = Carbon::createFromFormat('m/d/Y', $endDate);

        while ($startDateObj <= $endDateObj) {
            $date = $startDateObj->toDateString();
            $this->holidayRepository->createHoliday([
                'date' => $date,
                'title' => $title,
            ]);
            $startDateObj->addDay();
        }
    }

    public function update($data, $id)
    {
        $input = $data->only('title', 'daterange');
        if (strpos($input['daterange'], ' - ')) {
            $this->holidayRepository->delete($id);
            $title = $input['title'];
            $dateRange = $input['daterange'];
            list($startDate, $endDate) = explode(' - ', $dateRange);

            $startDateObj = Carbon::createFromFormat('m/d/Y', $startDate);
            $endDateObj = Carbon::createFromFormat('m/d/Y', $endDate);

            while ($startDateObj <= $endDateObj) {
                $date = $startDateObj->toDateString();
                $this->holidayRepository->createHoliday([
                    'date' => $date,
                    'title' => $title,
                ]);
                $startDateObj->addDay();
            }
        } else {
            $this->holidayRepository->update($input, $id);
        }
    }

    public function import(UploadedFile $file)
    {
        $contents = file_get_contents($file->path());
        $lines = explode("\n", $contents);

        array_shift($lines);

        foreach ($lines as $line) {
            $data = str_getcsv($line);
            $dateRange = $data[0];
            $title = $data[1];


            $dateParts = explode('/', $dateRange);
            $date = $dateParts[2] . '-' . $dateParts[1] . '-' . $dateParts[0];

            $this->holidayRepository->createHoliday([
                'date' => $date,
                'title' => $title,
            ]);
        }
    }

    public function delete($request)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $this->holidayRepository->delete($id);
        }
    }

    public function destroy($id)
    {
        $this->holidayRepository->delete($id);
    }
}
