<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use App\Models\Bread;
use App\Models\BreadJoin as BreadJoins;
use Illuminate\Support\Facades\DB;

class BreadJoin extends Component
{
    protected $listeners = [
        'delete' => 'destroy',
        'refresh' => '$refresh',
        'statusUpdate',
        'isCreate',
    ];
    public $searchable = [
        'breads.name', 'origin_table', 'origin_key',
        'foreign_table', 'foreign_key', 'join_type',
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
    public $search = '',
        $paginate = 10,
        $orderBy = 'name',
        $order = 'asc',
        $statusUpdate = false,
        $bread_id,
        $list_db_table,
        $list_origin_field = [],
        $list_foreign_field = [],
        $crud_value = [];

    public function mount($id)
    {
        $this->bread_id = $id;

        // Bread detail
        $bread = Bread::find($id);

        $this->crud_value = [
            'bread_id' => $id,
            'origin_table' => $bread->table_name,
            'origin_key' => $bread->primary_key,
        ];
    }

    // Rules input
    protected function rules()
    {
        return [
            'crud_value.bread_id' => 'required',
            'crud_value.origin_table' => 'required',
            'crud_value.origin_key' => 'required',
            'crud_value.foreign_table' => 'required',
            'crud_value.foreign_key' => 'required',
            'crud_value.join_type' => 'required',
        ];
    }

    // Message rules input
    protected function messages()
    {
        return [
            'crud_value.bread_id.required' => 'Bread is required',
            'crud_value.origin_table.required' => 'Origin table is required',
            'crud_value.origin_key.required' => 'Origin key is required',
            'crud_value.foreign_table.required' => 'Foreign table is required',
            'crud_value.foreign_key.required' => 'Foreign key is required',
            'crud_value.join_type.required' => 'Join type is required',
        ];
    }

    public function render()
    {
        // Get bread detail
        $bread_id = $this->bread_id;
        $bread = Bread::find($bread_id);
        $sql = BreadJoins::leftJoin('breads', 'breads.id', '=', 'bread_joins.bread_id')
        ->orderBy($this->orderBy, $this->order)
        ->where('bread_joins.bread_id', $bread_id)
        ->select('bread_joins.*', 'breads.name as bread')
        ->latest();
        $data = $sql->paginate($this->paginate);
        $title = $bread->name . ' Join';

        // Active menu
        $active_menu = ['BREAD', $title];

        // Search data
        if ($this->search != null) {
            $data = $sql;

            foreach ($this->searchable as $field) {
                $data = $data->orWhere($field, 'like', "%{$this->search}%");
            }

            $data = $data->paginate($this->paginate);

            $this->resetPage();
        }

        // Get list db table
        $this->list_db_table = DB::select('SHOW TABLES');
        $this->list_origin_field = DB::select('SHOW COLUMNS FROM '.$this->crud_value['origin_table']);

        return view('livewire.admin.bread-join', compact('data', 'bread', 'title'))->layoutData(compact('active_menu'));
    }

    // Order by
    public function changeOrder($orderBy)
    {
        if ($this->orderBy == $orderBy) {
            $this->order = $this->order == 'desc' ? 'asc' : 'desc';
        }

        $this->orderBy = $orderBy;
    }

    // Delete data
    public function destroy($id)
    {
        $exe = BreadJoins::find($id);
        $exe->delete();

        $this->emit('alert', 'Delete data success');
    }

    // Confirm delete
    public function confirmDelete($id)
    {
        $this->emit('confirm', $id);
    }

    // Is Create
    public function isCreate()
    {
        $this->statusUpdate = false;
    }

    // Is Update
    public function isUpdate($id)
    {
        $this->statusUpdate = true;
        $this->crud_value = BreadJoins::find($id)->toArray();
        $this->getDetailTable('origin');
        $this->getDetailTable('foreign');
    }

    // Get detail table data
    public function getDetailTable($type)
    {
        switch($type) {
            case 'origin':
                $this->list_origin_field = DB::select('SHOW COLUMNS FROM '.$this->crud_value['origin_table']);
                break;
            case 'foreign':
                $this->list_foreign_field = DB::select('SHOW COLUMNS FROM '.$this->crud_value['foreign_table']);
                break;
        }
    }

    // Save
    public function save()
    {
        $alert_message;

        if($this->statusUpdate) {
            $exe = BreadJoins::find($this->crud_value['id'])->update($this->crud_value);

            $alert_message = 'Data updated';
        } else {
            $exe = BreadJoins::create($this->crud_value);

            $alert_message = 'Data created';
        }

        $this->emit('alert', $alert_message);
        $this->emit('closeModal');
    }
}
