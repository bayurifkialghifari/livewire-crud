<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Navigation extends Component
{
    public $active_menu;

    public function render()
    {
        return view('livewire.navigation');
    }
}
