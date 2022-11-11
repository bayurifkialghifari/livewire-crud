<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ModalCrud extends Component
{
    public $title;
    public $modal_class = '';
    public $modal_size = 'md';
    public $button_close = true;
    public $button = '';
    public $content = '';
    public $is_submit = true;
    public $is_bread = true;
    public $statusUpdate = false;
    public $bread_slug = '';
    public $crud;
    public $crud_field;
    // public $crud_rules = []; Belum ada
    public $curd_value = [];

    public function render()
    {
        // Check crud model and field
        if ($this->is_bread) {
            $this->crud = Bread::where('url_slug', $this->bread_slug)->first();
            $this->crud_field = BreadField::where('bread_id', $this->crud->id)->get();
        }

        return view('livewire.modal-crud');
    }

    // Save data
    public function save()
    {
        $this->emit('alert', 'ggw');
        $this->emit('closeModal');
    }

    // Get detail data
    public function getDetail($id)
    {

    }
}
