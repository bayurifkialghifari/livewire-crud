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
        $is_foreign = 0,
        $crud_new = [],
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
        $this->getBreadDetail();
    }

    public function getBreadDetail()
    {
        $bread = Bread::find($this->bread_id);
        $bread_field = BreadFields::where('bread_id', $this->bread_id)->get();

        $this->crud_value['bread_id'] = $this->bread_id;
        $this->primary_table = $bread->table_name;
        $this->primary_key = $bread->primary_key;

        // Initiate crud value
        foreach ($bread_field as $bf) {
            $this->crud_value['foreign_table'][$bf->id] = $bf->foreign_table;
            $this->crud_value['foreign_key'][$bf->id] = $bf->foreign_key;
            $this->crud_value['foreign_field'][$bf->id] = $bf->foreign_field;
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
        ->select('bread_fields.*', 'breads.table_name as bread')
        ->where('bread_fields.bread_id', $bread_id);
        $data = $sql->get();
        $title = ucwords($bread->table_name) . ' Fields';

        // Active menu
        $active_menu = ['BREAD', $title];

        // Get list db table
        $this->list_db_table = DB::select('SHOW TABLES');
        $this->list_origin_field = DB::select('SHOW COLUMNS FROM ' . $bread->table_name);
        array_push($this->exluded_table, $bread->table_name);

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

    // Get detail table
    public function getDetailTable()
    {
        $this->list_foreign_field = DB::select('SHOW COLUMNS FROM ' . $this->crud_new['foreign_table']);
    }

    // Update is foreign
    public function updateForeign()
    {
        $this->crud_new['foreign_table'] = null;
        $this->crud_new['foreign_key'] = null;
        $this->crud_new['field'] = null;
        $this->crud_new['foreign_field'] = null;
    }

    // isCreate new field
    public function isCreate()
    {
        $this->crud_new = [
            'bread_id' => $this->bread_id,
            'foreign_table' => null,
            'foreign_key' => null,
            'foreign_field' => null,
            'field' => null,
            'type' => 'text',
            'is_required' => 0,
            'is_readonly' => 0,
            'is_searchable' => 1,
            'is_browse' => 1,
            'is_edit' => 1,
            'is_add' => 1,
        ];
    }

    // Create new field
    public function createNewField()
    {
        // Create
        $exe = BreadFields::create($this->crud_new);

        $this->getBreadDetail();
        $this->emit('alert', 'Data created');
        $this->emit('closeModal');
    }

    // Save
    public function save()
    {
        // Get key
        foreach($this->crud_value['type'] as $k => $v) {
            // Update
            $exe = BreadFields::find($k)->update([
                'foreign_table' => $this->crud_value['foreign_table'][$k],
                'foreign_key' => $this->crud_value['foreign_key'][$k],
                'foreign_field' => $this->crud_value['foreign_field'][$k],
                'type' => $this->crud_value['type'][$k],
                'display_name' => $this->crud_value['display_name'][$k],
                'placeholder' => $this->crud_value['placeholder'][$k],
                'class_alt' => $this->crud_value['class_alt'][$k],
                'default_value' => $this->crud_value['default_value'][$k],
                'id_alt' => $this->crud_value['id_alt'][$k],
                'is_required' => $this->crud_value['is_required'][$k],
                'is_readonly' => $this->crud_value['is_readonly'][$k],
                'is_searchable' => $this->crud_value['is_searchable'][$k],
                'is_browse' => $this->crud_value['is_browse'][$k],
                'is_edit' => $this->crud_value['is_edit'][$k],
                'is_add' => $this->crud_value['is_add'][$k],
                'source' => $this->crud_value['source'][$k],
                'source_id' => $this->crud_value['source_id'][$k],
                'source_value' => $this->crud_value['source_value'][$k],
                'file_accept' => $this->crud_value['file_accept'][$k],
                'description' => $this->crud_value['description'][$k],
                'description_class' => $this->crud_value['description_class'][$k],
            ]);
        }

        $this->emit('alert', 'Data saved successfully');
    }
}
