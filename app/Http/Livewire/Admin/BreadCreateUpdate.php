<?php

namespace App\Http\Livewire\Admin;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Bread;
use App\Models\BreadField;
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
                'url_slug' => $detail->url_slug,
                'display_name_singular' => $detail->display_name_singular,
                'display_name_plural' => $detail->display_name_plural,
                'table_name' => $detail->table_name,
                'primary_key' => $detail->primary_key,
                'order' => $detail->order,
                'is_join' => $detail->is_join,
                'active_menu' => $detail->active_menu,
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
            'values.url_slug' => 'required',
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
            'values.url_slug.required' => 'URL is required',
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
            $this->createBreadField($exe->id);
        }

        // Alert
        $this->emit('alert', $message);
        $this->emit('redirect', url('admin/bread'));
    }

    // Set singular and plural
    public function getSingularPlural() {
        $this->values['display_name_plural'] = Str::plural($this->values['name']);
        $this->values['display_name_singular'] = Str::singular($this->values['name']);
        $this->values['url_slug'] = Str::slug($this->values['name']);
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

    // Create bread field
    public function createBreadField($id) {
        $columns = [];
        foreach($this->columns as $i => $cl) {
            $field = $cl->Field ?? $cl['Field'];
            // Not create bread field for created_at and updated_at
            if($field != 'created_at' && $field != 'updated_at') {
                // 'foreign_table' => null,
                // 'foreign_key' => null,
                // 'foreign_field' => null,
                array_push($columns, [
                    'bread_id' => $id,
                    'field' => $field,
                    'type' => $field == $this->values['primary_key'] ? 'hidden' : $this->checkType($cl->Type ?? $cl['Type']),
                    'display_name' => Str::title($field),
                    'placeholder' => '',
                    'class_alt' => '',
                    'default_value' => '',
                    'id_alt' => '',
                    'is_required' => 0,
                    'is_readonly' => 0,
                    'is_searchable' => 1,
                    'is_browse' => 1,
                    'is_edit' => 1,
                    'is_add' => 1,
                    'source' => '',
                    'source_id' => '',
                    'source_value' => '',
                    'file_accept' => '',
                    'description' => '',
                    'description_class' => '',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $exe = BreadField::insert($columns);
    }

    // Check type
    public function checkType($field_type) {
        $type = 'text';

        if(strpos($field_type, 'int')) {
            $type = 'number';
        } else if(strpos($field_type, 'char')) {
            $type = 'text';
        } else if(strpos($field_type, 'text')) {
            $type = 'textarea';
        } else if(strpos($field_type, 'date')) {
            $type = 'date';
        } else if(strpos($field_type, 'time')) {
            $type = 'time';
        }

        return $type;
    }
}
