<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       $this->call([
        //    SettingSeeder::class,
           UserSeeder::class,
        //    HolidaySeeder::class,
       ]);
        $this->call(PermissionRoles::class);
    }
}
