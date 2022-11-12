<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RoleHasMenu;

class RoleMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Role menu
        $role_menu = [
            [
                'role_id' => 1,
                'menu_id' => 1,
                'sub_menu_id' => null,
            ],
            [
                'role_id' => 1,
                'menu_id' => 2,
                'sub_menu_id' => null,
            ],
            [
                'role_id' => 1,
                'menu_id' => null,
                'sub_menu_id' => 1,
            ],
            [
                'role_id' => 1,
                'menu_id' => null,
                'sub_menu_id' => 2,
            ],
            [
                'role_id' => 1,
                'menu_id' => null,
                'sub_menu_id' => 3,
            ],
        ];

        // Create role menu
        $role_menu = RoleHasMenu::insert($role_menu);
    }
}
