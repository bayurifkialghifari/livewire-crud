<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Menu;

class Navigation extends Component
{
    public $active_menu;

    public function render()
    {
        $menu = Menu::all();

        return view('livewire.navigation', compact('menu'));
    }
}
