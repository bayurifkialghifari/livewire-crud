<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // Active menu
        $active_menu = ['Dashboard'];

        return view('livewire.admin.dashboard')->layoutData(compact(
            'active_menu'
        ));
    }
}
