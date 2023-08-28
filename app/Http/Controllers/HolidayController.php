<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HolidayService;
use Laracasts\Flash\Flash;

class HolidayController extends Controller
{
    protected $holidayService;

    public function __construct(HolidayService $holidayService)
    {
        $this->holidayService = $holidayService;
    }

    public function index(Request $request)
    {
        $holidayData = $this->holidayService->getHolidays($request);
        $export = $holidayData->get();
        $holidays = $holidayData->paginate(10);
        return view('holiday.index', compact('holidays', 'export'));
    }

    public function calendar()
    {
        $events = $this->holidayService->getAllHolidays();
        return view('holiday.calendar', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'daterange' => 'regex:/\d{2}\/\d{2}\/\d{4}\s-\s\d{2}\/\d{2}\/\d{4}/',
        ]);

        $this->holidayService->store($request);
        return redirect()->route('holidays.index')->with('success', trans('validation.crud.created'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' =>
            [
                'required',
                'file',
                'mimes:csv,txt',
            ],
        ]);
        $file = $request->file('csv_file');
        $this->holidayService->import($file);

        return redirect()->route('holidays.index')->with('success', trans('validation.crud.imported'));
    }

    public function export(Request $request)
    {
        $exportData = json_decode($request->input('export_data'), true);
        return $this->holidayService->export($exportData);
    }

    public function edit($id)
    {
        $holiday = $this->holidayService->getHoliday($id);
        return response()->json($holiday);
    }

    public function update($id, Request $request)
    {
        $this->holidayService->update($request, $id);
        return redirect()->route('holidays.index')->with('success', trans('validation.crud.updated'));
    }

    public function delete(Request $request)
    {
        $this->holidayService->delete($request);
        Flash::success(trans('validation.crud.delete'));
        return redirect(route('holidays.index'));
    }

    public function destroy($id)
    {
        $this->holidayService->destroy($id);
        Flash::success(trans('validation.crud.delete'));
        return redirect(route('holidays.index'));
    }
}
