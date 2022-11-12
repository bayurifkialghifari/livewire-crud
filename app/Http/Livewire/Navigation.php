<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Menu;
use App\Models\RoleHasMenu;

class Navigation extends Component
{
    public $active_menu;

    public function render()
    {
        $menu = Menu::all();
        $role_has_menu = new RoleHasMenu;

        return view('livewire.navigation', compact('menu', 'role_has_menu'));
    }
}
