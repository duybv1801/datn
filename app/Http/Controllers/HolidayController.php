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

    public function index()
    {
        $holidays = $this->holidayService->getAllHolidays();

        return view('holiday.index', compact('holidays'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'date',
            'daterange' => 'regex:/\d{2}\/\d{2}\/\d{4}\s-\s\d{2}\/\d{2}\/\d{4}/',
        ]);

        $this->holidayService->store($request);
        return redirect()->route('holidays.index')->with('success', trans('validation.crud.created'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'csv_file' =>
            [
                'required',
                'file',
                'mimes:csv,txt',
            ],
        ]);
        $file = $request->file('csv_file');
        $year = $request->year;
        $this->holidayService->import($file, $year);

        return redirect()->route('holidays.index')->with('success', trans('validation.crud.updated'));
    }

    public function destroy($id)
    {
        $this->holidayService->destroy($id);
        Flash::success(trans('validation.crud.delete'));
        return redirect(route('holidays.index'));
    }
}
