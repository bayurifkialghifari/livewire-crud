<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\SubMenu;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menu
        $menu = [
            [
                'id' => '1',
                'name' => 'Dashboard',
                'url' => '/admin',
                'icon' => 'bi bi-grid-fill',
            ],
            [
                'id' => '2',
                'name' => 'Setting',
                'url' => '#',
                'icon' => 'bi bi-wrench',
            ],
        ];

        // Create menu
        $menu = Menu::insert($menu);

        // Sub menu
        $sub_menu = [
            [
                'menu_id' => '2',
                'name' => 'Role',
                'url' => '/admin/setting-role',
            ],
            [
                'menu_id' => '2',
                'name' => 'Menu',
                'url' => '/admin/setting-menu',
            ],
            [
                'menu_id' => '2',
                'name' => 'User',
                'url' => '/admin/setting-user',
            ],
        ];

        // Create sub menu
        $sub_menu = SubMenu::insert($sub_menu);
    }
}
