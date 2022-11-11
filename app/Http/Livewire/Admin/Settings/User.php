<?php

namespace App\Http\Livewire\Admin\Settings;

use Livewire\Component;

class User extends Component
{
    public function render()
    {
        // Active menu
        $active_menu = ['Setting', 'Role'];

        return view('livewire.admin.settings.user')->layoutData(compact('active_menu'));
    }
}
