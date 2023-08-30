<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Holiday;

class HolidaySeeder extends Seeder
{

    public function run()
    {
        $currentYear = date('Y');

        $fixedHolidays = [
            ['date' => '01-01', 'title' => 'Tết Dương lịch'],
            ['date' => '02-14', 'title' => 'Tết Nguyên Đán'],
            ['date' => '03-10', 'title' => 'Giỗ Tổ Hùng Vương'],
            ['date' => '04-30', 'title' => 'Thống nhất đất nước'],
            ['date' => '05-01', 'title' => 'Quốc tế Lao động'],
            ['date' => '09-02', 'title' => 'Quốc khánh'],
        ];

        $data = [];

        foreach ($fixedHolidays as $holiday) {
            $data[] = [
                'date' => "$currentYear-{$holiday['date']}",
                'title' => $holiday['title'],
            ];
        }

        Holiday::upsert($data, ['date'], ['title']);
    }
}
