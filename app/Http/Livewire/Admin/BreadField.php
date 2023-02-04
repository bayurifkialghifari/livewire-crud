<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Bread;
use App\Models\BreadField as BreadFields;
use Illuminate\Support\Facades\DB;

class BreadField extends Component
{
    protected $listeners = [
        'delete' => 'destroy',
        'refresh' => '$refresh',
    ];
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
    public $bread_id,
        $primary_table,
        $primary_key,
        $list_db_table,
        $list_origin_field = [],
        $list_foreign_field = [],
        $crud_value = [
            'bread_id' => 0,
            'type' => [],
            'display_name' => [],
            'placeholder' => [],
            'class_alt' => [],
            'default_value' => [],
            'id_alt' => [],
            'is_required' => [],
            'is_readonly' => [],
            'is_searchable' => [],
            'is_browse' => [],
            'is_edit' => [],
            'is_add' => [],
            'source' => [],
            'source_id' => [],
            'source_value' => [],
            'file_accept' => [],
            'description' => [],
            'description_class' => [],
        ];

    public function mount($id)
    {
        $this->bread_id = $id;

        // Bread detail
        $bread = Bread::find($id);
        $bread_field = BreadFields::where('bread_id', $id)->get();

        $this->crud_value['bread_id'] = $id;
        $this->primary_table = $bread->table_name;
        $this->primary_key = $bread->primary_key;

        // Initiate crud value
        foreach ($bread_field as $bf) {
            $this->crud_value['type'][$bf->id] = $bf->type;
            $this->crud_value['display_name'][$bf->id] = $bf->display_name;
            $this->crud_value['placeholder'][$bf->id] = $bf->placeholder;
            $this->crud_value['class_alt'][$bf->id] = $bf->class_alt;
            $this->crud_value['default_value'][$bf->id] = $bf->default_value;
            $this->crud_value['id_alt'][$bf->id] = $bf->id_alt;
            $this->crud_value['is_required'][$bf->id] = $bf->is_required;
            $this->crud_value['is_readonly'][$bf->id] = $bf->is_readonly;
            $this->crud_value['is_searchable'][$bf->id] = $bf->is_searchable;
            $this->crud_value['is_browse'][$bf->id] = $bf->is_browse;
            $this->crud_value['is_edit'][$bf->id] = $bf->is_edit;
            $this->crud_value['is_add'][$bf->id] = $bf->is_add;
            $this->crud_value['source'][$bf->id] = $bf->source;
            $this->crud_value['source_id'][$bf->id] = $bf->source_id;
            $this->crud_value['source_value'][$bf->id] = $bf->source_value;
            $this->crud_value['file_accept'][$bf->id] = $bf->file_accept;
            $this->crud_value['description'][$bf->id] = $bf->description;
            $this->crud_value['description_class'][$bf->id] = $bf->description_class;
        }
    }

    public function render()
    {
        // Get bread detail
        $bread_id = $this->bread_id;
        $bread = Bread::find($bread_id);
        $sql = BreadFields::leftJoin('breads', 'breads.id', '=', 'bread_fields.bread_id')
        ->select('bread_fields.*', 'breads.table_name as bread');
        $data = $sql->get();
        $title = ucwords($bread->table_name) . ' Fields';

        // Active menu
        $active_menu = ['BREAD', $title];

        // Get list db table
        $this->list_db_table = DB::select('SHOW TABLES');
        $this->list_origin_field = DB::select('SHOW COLUMNS FROM '.$this->primary_table);

        return view('livewire.admin.bread-field', compact('data', 'bread', 'title'))->layoutData(compact('active_menu'));
    }

    // Delete data
    public function destroy($id)
    {
        $exe = BreadFields::find($id);
        $exe->delete();

        $this->emit('alert', 'Delete data success');
    }

    // Confirm delete
    public function confirmDelete($id)
    {
        $this->emit('confirm', $id);
    }
}
