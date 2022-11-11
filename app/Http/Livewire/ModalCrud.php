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
    public $crud_field = [];
    public $crud_rules = [];
    public $crud_value = [];

    public function render()
    {
        // Check crud model and field
        if ($this->is_bread) {
            $this->crud = Bread::where('url_slug', $this->bread_slug)->first();
            $this->crud_field = BreadField::where('bread_id', $this->crud->id)->get();
        }

        /**
         *
         * Set rules from bread field
         *
         */
        foreach ($this->crud_field as $cf) {
            $cf = (object) $cf;

            // Check if rules exist
            if(count($cf->rules) > 0) {
                $this->crud_rules = array_merge($this->crud_rules, $cf->rules);
            }
        }

        return view('livewire.modal-crud');
    }

    // Rules input
    protected function rules()
    {
        return $this->crud_rules;
    }

    // Save data
    public function save()
    {
        // Validate if rules > 0
        if(count($this->crud_rules) > 0) {
            $this->validate();
        }

        $this->emit('alert', 'ggw');
        $this->emit('closeModal');
    }

    // Get detail data
    public function getDetail($id)
    {
    }
}
