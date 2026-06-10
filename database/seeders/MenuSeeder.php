<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('menus')->insert([
            [
                'id' => 1,
                'title' => 'Manage',
                'icon' => 'fa fa-cog',
                'route' => null,
                'permission_name' => null,
                'parent_id' => null,
                'order' => 1,
            ],
            [
                'id' => 2,
                'title' => 'Pengguna',
                'icon' => 'fa fa-circle',
                'route' => 'users.index',
                'permission_name' => 'user-list',
                'parent_id' => 1,
                'order' => 1,
            ],
            [
                'id' => 3,
                'title' => 'Menu',
                'icon' => 'fa fa-circle',
                'route' => 'menus.index',
                'permission_name' => 'menu-list',
                'parent_id' => 1,
                'order' => 2,
            ],
            [
                'id' => 4,
                'title' => 'Hak Akses',
                'icon' => 'fa fa-circle',
                'route' => 'roles.index',
                'permission_name' => 'role-list',
                'parent_id' => 1,
                'order' => 3,
            ],
            [
                'id' => 5,
                'title' => 'Pengaturan Apps',
                'icon' => 'fa fa-circle',
                'route' => 'setting-app.index',
                'permission_name' => 'setting-app-list',
                'parent_id' => 1,
                'order' => 4,
            ],
            [
                'id' => 6,
                'title' => 'Master',
                'icon' => 'fa fa-folder-open',
                'route' => null,
                'permission_name' => null,
                'parent_id' => null,
                'order' => 2,
            ],
            // [
            //     'id'              => 7,
            //     'title'           => 'Produk',
            //     'icon'            => 'fa fa-circle',
            //     'route'           => 'products.index',
            //     'permission_name' => 'product-list',
            //     'parent_id'       => 6,
            //     'order'           => 1,
            // ],
        ]);

        DB::statement("
            SELECT setval(
                pg_get_serial_sequence('menus', 'id'),
                COALESCE((SELECT MAX(id) FROM menus), 1)
            );
        ");
    }
}
