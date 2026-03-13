<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Pengguna
            ['menu_id' => 2, 'name' => 'user-list', 'guard_name' => 'web'],
            ['menu_id' => 2, 'name' => 'user-create', 'guard_name' => 'web'],
            ['menu_id' => 2, 'name' => 'user-edit', 'guard_name' => 'web'],
            ['menu_id' => 2, 'name' => 'user-delete', 'guard_name' => 'web'],

            // Menu
            ['menu_id' => 3, 'name' => 'menu-list', 'guard_name' => 'web'],
            ['menu_id' => 3, 'name' => 'menu-create', 'guard_name' => 'web'],
            ['menu_id' => 3, 'name' => 'menu-edit', 'guard_name' => 'web'],
            ['menu_id' => 3, 'name' => 'menu-delete', 'guard_name' => 'web'],

            // Hak Akses
            ['menu_id' => 4, 'name' => 'role-list', 'guard_name' => 'web'],
            ['menu_id' => 4, 'name' => 'role-create', 'guard_name' => 'web'],
            ['menu_id' => 4, 'name' => 'role-edit', 'guard_name' => 'web'],
            ['menu_id' => 4, 'name' => 'role-delete', 'guard_name' => 'web'],

            // Pengaturan Apps
            ['menu_id' => 5, 'name' => 'setting-app-list', 'guard_name' => 'web'],
            ['menu_id' => 5, 'name' => 'setting-app-create', 'guard_name' => 'web'],
            ['menu_id' => 5, 'name' => 'setting-app-edit', 'guard_name' => 'web'],
            ['menu_id' => 5, 'name' => 'setting-app-delete', 'guard_name' => 'web'],

            // Produk
            ['menu_id' => 7, 'name' => 'product-list', 'guard_name' => 'web'],
            ['menu_id' => 7, 'name' => 'product-create', 'guard_name' => 'web'],
            ['menu_id' => 7, 'name' => 'product-edit', 'guard_name' => 'web'],
            ['menu_id' => 7, 'name' => 'product-delete', 'guard_name' => 'web'],
        ];

        DB::table('permissions')->insert($permissions);
    }
}
