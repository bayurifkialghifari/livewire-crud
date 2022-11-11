<?php

namespace App\Http\Livewire\Admin\Settings;

use Livewire\Component;

class Menu extends Component
{
    public function render()
    {
        // Active menu
        $active_menu = ['Setting', 'Menu'];

        return view('livewire.admin.settings.menu')->layoutData(compact(
            'active_menu'
        ));
    }
}
