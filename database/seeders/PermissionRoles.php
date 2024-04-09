<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoles extends Seeder
{
    public function run()
    {
        // Tạo roles
        // DB::table('roles')->insert([
        //     ['name' => 'admin'],
        //     ['name' => 'member'],
        //     ['name' => 'accounter'],
        //     ['name' => 'hr'],
        //     ['name' => 'po']
        // ]);

        DB::table('role_user')->insert([
            [
                'role_id' => 2,
                'user_id' => 2,
            ],
        ]);

//        DB::table('permissions')->insert([
//            ['name' => 'read'],
//            ['name' => 'create'],
//            ['name' => 'update'],
//            ['name' => 'delete'],
//        ]);


//        DB::table('permission_role')->insert([
//            [
//                'role_id' => 1,
//                'permission_id' => 1,
//            ],
//            [
//                'role_id' => 1,
//                'permission_id' => 2,
//            ],
//            [
//                'role_id' => 1,
//                'permission_id' => 3,
//            ],
//            [
//                'role_id' => 1,
//                'permission_id' => 4,
//            ],
//        ]);


//        DB::table('permission_role')->insert([
//            [
//                'role_id' => 2,
//                'permission_id' => 1,
//            ],
//            [
//                'role_id' => 2,
//                'permission_id' => 3,
//            ],
//        ]);
//
//        DB::table('permission_role')->insert([
//            [
//                'role_id' => 3,
//                'permission_id' => 1,
//            ],
//            [
//                'role_id' => 3,
//                'permission_id' => 3,
//            ],
//        ]);
//
//        DB::table('permission_role')->insert([
//            [
//                'role_id' => 4,
//                'permission_id' => 1,
//            ],
//            [
//                'role_id' => 4,
//                'permission_id' => 3,
//            ],
//        ]);
    }
}
