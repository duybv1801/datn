<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoles extends Seeder
{
    public function run()
    {
        // Tạo roles
        DB::table('roles')->insert([
            ['name' => 'admin'],
            ['name' => 'member'],
            ['name' => 'accounter'],
            ['name' => 'hr'],
            ['name' => 'po']
        ]);
    }
}
