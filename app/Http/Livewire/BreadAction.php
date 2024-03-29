<?php

namespace App\Http\Livewire;

use App\Models\Bread;
use App\Models\BreadField;
use App\Models\BreadJoin;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Livewire\Component;

class BreadAction extends Component
{
    use WithPagination;

    protected $listeners = [
        'delete' => 'destroy',
        'refresh' => '$refresh',
        'isUpdate',
        'isCreate',
    ];
    public $searchable = [];
    public $displayed = [];
    public $search = '',
        $bread_id,
        $bread_slug,
        $bread_detail = [],
        $table_name,
        $fields = [],
        $paginate = 10,
        $orderBy = '',
        $order = '',
        $crud_field = [],
        $crud_value = [],
        $crud_join = [],
        $update = false;

    // Mount data
    public function mount($slug) {
        // Get detail data
        $this->bread_detail = Bread::where('url_slug', $slug)->first();
        $this->fields = BreadField::where('bread_id', $this->bread_detail->id)->get();

        // Set data
        $this->bread_slug = $slug;
        $this->bread_id = $this->bread_detail->id;
        $this->table_name = $this->bread_detail->table_name;
        $this->orderBy = $this->bread_detail->table_name . '.' . $this->bread_detail->order_by;
        $this->order = $this->bread_detail->order;

        foreach ($this->fields as $field) {
            // Searchable fields
            if($field->is_searchable == 1) {
                // If foreign
                if ($field->foreign_table) {
                    array_push($this->searchable, $field->foreign_table . '.' . $field->foreign_field);
                //
                } else {
                    array_push($this->searchable, $this->table_name . '.' . $field->field);
                }
            }

            // Displayed fields
            if($field->is_browse == 1) {
                // If foreign
                if ($field->foreign_table) {
                    array_push($this->displayed, $field->foreign_table . '.' . $field->foreign_field . ' as ' . $field->foreign_table . $field->foreign_field);
                //
                } else {
                    array_push($this->displayed, $this->table_name . '.' . $field->field);
                }
            }

            // Crud field and value
            $this->crud_value[$field->foreign_table ? $field->foreign_table . '-' . $field->foreign_field  : $field->field] = '';
            array_push($this->crud_field, [
                'field' => $field->foreign_table ? $field->foreign_table . '-' . $field->foreign_field  : $field->field,
                'foreign_table' => $field->foreign_table,
                'foreign_key' => $field->foreign_key,
                'foreign_field' => $field->foreign_field,
                'type' => $field->type,
                'display_name' => $field->display_name,
                'placeholder' => $field->placeholder,
                'class_alt' => $field->class_alt,
                'id_alt' => $field->id_alt,
                'is_required' => $field->is_required,
                'description' => $field->description,
                'description_class' => $field->description_class,
                'file_accept' => $field->type == 'file' ? $field->file_accept : null,
                'default_value' => $field->default_value,
                'source' => $field->field == 'select' ? $field->source : null,
                'source_id' => $field->field == 'select' ? $field->source_id : null,
                'source_value' => $field->field == 'select' ? $field->source_value : null,
            ]);
        }
    }

    public function render()
    {
        // Active menu
        $active_menu = explode(',', $this->bread_detail->active_menu);

        // Get data
        $sql = DB::table($this->table_name)->select($this->displayed);

        // Check if is join
        if($this->bread_detail->is_join == 1) {
            $table_join = BreadJoin::where('bread_id', $this->bread_detail->id)->get();

            // Join the table
            foreach($table_join as $tj) {
                // Left
                if($tj->join_type == 'left') {
                    $sql->leftJoin($tj->foreign_table, $tj->foreign_table . '.' . $tj->foreign_key, '=', $tj->origin_table . '.' . $tj->origin_key);
                // Right
                } else if($tj->join_type == 'right') {
                    $sql->rightJoin($tj->foreign_table, $tj->foreign_table . '.' . $tj->foreign_key, '=', $tj->origin_table . '.' . $tj->origin_key);
                } else {
                    $sql->join($tj->foreign_table, $tj->foreign_table . '.' . $tj->foreign_key, '=', $tj->origin_table . '.' . $tj->origin_key);
                }

                // Crud join
                array_push($this->crud_join, [
                    'origin_table' => $tj->origin_table,
                    'origin_key' => $tj->origin_key,
                    'foreign_table' => $tj->foreign_table,
                    'foreign_key' => $tj->foreign_key,
                    'join_type' => $tj->join_type,
                ]);
            }
        }

        // Order by
        $sql = $sql->orderBy($this->orderBy, $this->order); // ->latest()
        $data = $sql->paginate($this->paginate);

        // Search data
        if ($this->search != null) {
            $data = $sql;

            foreach ($this->searchable as $field) {
                $data = $data->orWhere($field, 'like', "%{$this->search}%");
            }

            $data = $data->paginate($this->paginate);

            $this->resetPage();
        }

        return view('livewire.bread-action', compact('data', 'active_menu'))->layoutData(compact('active_menu'));;
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
        $exe = DB::table($this->table_name)->where($this->bread_detail->primary_key, $id);
        $exe->delete();

        $this->emit('alert', 'Delete data success');
    }

    // Confirm delete
    public function confirmDelete($id)
    {
        $this->emit('confirm', $id);
    }

    // Set status update true
    public function isUpdate($id)
    {
        $this->emitTo('modal-crud', 'getDetail', $id);
        $this->emitTo('modal-crud', 'isUpdate');
    }

    // Set status update false
    public function isCreate()
    {
        $this->emitTo('modal-crud', 'isCreate');
    }
}
