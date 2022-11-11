<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class ModalCrud extends Component
{
    // Function Listener
    protected $listeners = ['getDetail', 'isCreate', 'isUpdate'];

    public $title;
    public $modal_class = '';
    public $modal_size = 'md';
    public $button_close = true;
    public $button = '';
    public $content = '';
    public $is_join = false;
    public $is_submit = true;
    public $is_bread = true;
    public $table_name = '';
    public $statusUpdate = false;
    public $insert_message = 'Data created';
    public $update_message = 'Data updated';
    public $bread_slug = '';
    public $crud;
    public $crud_field = [];
    public $crud_rules = [];
    public $crud_rule_messages = [];
    public $crud_value = [];

    public function render()
    {
        // Check crud model and field
        if ($this->is_bread) {
            $this->crud = Bread::where('url_slug', $this->bread_slug)->first();
            $this->crud_field = BreadField::where('bread_id', $this->crud->id)->get();
        }

        return view('livewire.modal-crud');
    }

    // Rules input
    protected function rules()
    {
        return $this->crud_rules;
    }

    // Message rules input
    protected function messages()
    {
        return $this->crud_rule_messages;
    }

    // Save data
    public function save()
    {
        // Validate if rules > 0
        if (count($this->crud_rules) > 0) {
            $this->validate();
        }

        $model;
        $alert_message;

        // Check if bread
        if ($this->is_bread) {
            $model = DB::table($this->crud->table_name);
        } else {
            $model = DB::table($this->table_name);
        }

        // Is insert or update
        if ($this->statusUpdate) {
            // Timestamp
            $this->crud_value['updated_at'] = Carbon::now();

            $model->find($this->crud_value['id'])->update($this->crud_value);

            // Set alert message
            $alert_message = $this->update_message;
        } else {
            // Unset id
            unset($this->crud_value['id']);

            // Timestamp
            $this->crud_value['created_at'] = Carbon::now();
            $this->crud_value['updated_at'] = Carbon::now();

            $model->insert($this->crud_value);

            // Set id
            $this->crud_value['id'] = '';

            // Set alert message
            $alert_message = $this->insert_message;
        }

        $this->emit('refresh');
        $this->emit('alert', $alert_message);
        $this->emit('closeModal');
    }

    // Get detail data
    public function getDetail($id)
    {
        $model;

        // Check if bread
        if ($this->is_bread) {
            $model = DB::table($this->crud->table_name)->find($id);
        } else {
            $model = DB::table($this->table_name)->find($id);
        }

        // Set the detail data
        foreach ($this->crud_value as $key => $value) {
            $this->crud_value[$key] = $model->{$key};
        }
    }

    // Is Update
    public function isUpdate()
    {
        $this->statusUpdate = true;
    }

    // Is Create
    public function isCreate()
    {
        $this->statusUpdate = false;

        // Reset value data
        foreach ($this->crud_value as $key => $value) {
            $this->crud_value[$key] = '';
        }
    }
}
