<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Bread;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BreadCreateUpdate extends Component
{
    public $values = [];
    public $list_db_table;
    public $primary_key;
    // Excluded from list db table
    public $exluded_table = [
        'breads',
        'bread_joins',
        'bread_fields',
        'bread_field_rules',
        'migrations',
        'failed_jobs',
        'jobs',
        'model_has_permissions',
        'model_has_roles',
        'password_resets',
        'permissions',
        'personal_access_tokens',
        'role_has_menus',
        'role_has_permissions',
        'sessions',
        'menus',
        'sub_menus',
    ];
    // List all column from table
    public $columns = [];
    // is Update
    public $isUpdate = false;
    public $ids;

    // Mount data
    public function mount($id = null) {
        // Check update or create
        if($id) {
            $detail = Bread::find($id);

            $this->isUpdate = true;
            $this->ids = $id;

            // Set value
            $this->values = [
                'name' => $detail->name,
                'display_name_singular' => $detail->display_name_singular,
                'display_name_plural' => $detail->display_name_plural,
                'table_name' => $detail->table_name,
                'primary_key' => $detail->primary_key,
                'order' => $detail->order,
                'is_join' => $detail->is_join,
                'custom_controller' => $detail->custom_controller,
                'custom_button' => $detail->custom_button,
                'description' => $detail->description,
            ];

            // Set order by
            $this->getDetailTable();

            $this->values['order_by'] = $detail->order_by;
        } else {
            $this->values = [
                'order' => 'ASC',
                'is_join' => '0',
            ];
        }
    }

    // Rules input
    protected function rules()
    {
        return [
            'values.name' => 'required',
            'values.display_name_singular' => 'required',
            'values.display_name_plural' => 'required',
            'values.table_name' => 'required',
            'values.primary_key' => 'required',
            'values.order_by' => 'required',
            'values.order' => 'required',
        ];
    }

    // Message rules input
    protected function messages()
    {
        return [
            'values.name.required' => 'Name is required',
            'values.display_name_singular.required' => 'Singular Name is required',
            'values.display_name_plural.required' => 'Plural Name is required',
            'values.table_name.required' => 'Table name is required',
            'values.primary_key.required' => 'Primary key is required',
            'values.order_by.required' => 'Order by is required',
            'values.order.required' => 'Order is required',
        ];
    }

    public function render()
    {
        // Active menu
        $active_menu = ['BREAD'];

        // Get list table
        $this->list_db_table = DB::select('SHOW TABLES');

        return view('livewire.admin.bread-create-update')->layoutData(compact('active_menu'));
    }

    // Save data
    public function save() {
        $this->validate();

        $message;

        if($this->isUpdate) {
            $message = 'Data updated';
            $exe = Bread::find($this->ids);
            $exe = $exe->update($this->values);
        } else {
            $message = 'Data created';
            $exe = Bread::create($this->values);
        }

        // Alert
        $this->emit('alert', $message);
        $this->emit('redirect', url('admin/bread'));
    }

    // Set singular and plural
    public function getSingularPlural() {
        $this->values['display_name_plural'] = Str::plural($this->values['name']);
        $this->values['display_name_singular'] = Str::singular($this->values['name']);
    }

    // Get detail table data
    public function getDetailTable() {
        // Get primary key
        $primary_key = DB::select('SHOW KEYS FROM '.$this->values['table_name'].' WHERE Key_name = "PRIMARY"');
        $this->values['primary_key'] = $primary_key[0]->Column_name;

        // Reset order by
        $this->values['order_by'] = '';

        // Get column data
        $columns = DB::select('SHOW COLUMNS FROM '.$this->values['table_name']);
        $this->columns = $columns;
    }
}