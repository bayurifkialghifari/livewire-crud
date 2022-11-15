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
    public $join = [];
    public $is_submit = true;
    public $is_bread = true;
    public $table_name = '';
    public $primary_key = 'id';
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

        // Is join check crud value
        if($this->is_join) {
            $crud_value_temp = [];
            foreach ($this->crud_value as $key => $val) {
                if (strpos($key, '-')) {
                    $crud_value_temp[$key] = $val;
                    unset($this->crud_value[$key]);
                }
            }
        }

        // Check if password
        if (isset($this->crud_value['password'])) {
            $this->crud_value['password'] = bcrypt($this->crud_value['password']);
        }

        // Is insert or update
        if ($this->statusUpdate) {
            // Unset created at
            unset($this->crud_value['created_at']);

            // Timestamp
            $this->crud_value['updated_at'] = Carbon::now();

            $model->where($this->primary_key, $this->crud_value[$this->primary_key])->update($this->crud_value);

            // Set alert message
            $alert_message = $this->update_message;
        } else {
            // Unset id
            unset($this->crud_value[$this->primary_key]);

            // Timestamp
            $this->crud_value['created_at'] = Carbon::now();
            $this->crud_value['updated_at'] = Carbon::now();

            $model = $model->insertGetId($this->crud_value);

            // Set id
            $this->crud_value[$this->primary_key] = '';

            // Set alert message
            $alert_message = $this->insert_message;
        }

        // Is join create and update
        if ($this->is_join) {
            foreach ($this->crud_field as $cf) {
                if (strpos($cf['field'], '-')) {
                    $value = $crud_value_temp[$cf['field']];
                    // New model join
                    $model_join = DB::table($cf['foreign_table']);

                    // Is insert or update
                    if ($this->statusUpdate) {
                        $model_join->where($cf['foreign_key'], $this->crud_value[$this->primary_key])->update([
                            $cf['foreign_key'] => $this->crud_value[$this->primary_key],
                            $cf['foreign_field'] => $value,
                            // 'updated_at' => Carbon::now(),
                        ]);
                    } else {
                        $model_join->insert([
                            $cf['foreign_key'] => $model,
                            $cf['foreign_field'] => $value,
                            // 'created_at' => Carbon::now(),
                            // 'updated_at' => Carbon::now(),
                        ]);
                    }
                }
            }

            // Set value that unset before
            foreach($crud_value_temp as $key => $cvt) {
                $this->crud_value[$key] = '';
            }
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
            $model = DB::table($this->crud->table_name);
        } else {
            $model = DB::table($this->table_name);
        }

        // Select data
        $select = '*';

        // Check if bread is join
        if ($this->is_join) {
            foreach ($this->join as $join) {
                // Check type join
                switch ($join['join_type']) {
                    case 'left':
                        $model = $model->leftJoin($join['origin_table'], $join['origin_table'] . '.' . $join['origin_key'], '=', $join['foreign_table'] . '.' . $join['foreign_key']);
                        break;
                    case 'right':
                        $model = $model->rightJoin($join['origin_table'], $join['origin_table'] . '.' . $join['origin_key'], '=', $join['foreign_table'] . '.' . $join['foreign_key']);
                        break;
                    default:
                        $model = $model->join($join['origin_table'], $join['origin_table'] . '.' . $join['origin_key'], '=', $join['foreign_table'] . '.' . $join['foreign_key']);
                }
            }

            // Select data if join
            $select = [];
            foreach ($this->crud_value as $key => $select_join) {
                if (strpos($key, '-')) {
                    $select_key = explode('-', $key);
                    $select[] = $select_key[0] . '.' . $select_key[1] . ' as ' . $key;
                } else {
                    $select[] = $this->table_name . '.' . $key;
                }
            }
        }

        $model = $model
            ->select($select)
            ->where($this->table_name . '.' . $this->primary_key, $id)
            ->first();

        // Std class to qrray
        $model = json_decode(json_encode($model), true);

        // Set id
        $this->crud_value[$this->primary_key] = $id;
        // Set the detail data
        foreach ($this->crud_field as $key => $val) {
            // Is join
            if($this->is_join) {
                if (strpos($val['field'], '-')) {
                    $this->crud_value[$val['field']] = $model[$val['field']];
                } else {
                    $this->defaultSet($val, $model);
                }
            } else {
                $this->defaultSet($val, $model);
            }
        }
    }

    // Default set get detail
    public function defaultSet($val, $model) {
        if ($val['type'] !== 'password') {
            $this->crud_value[$val['field']] = $model[$val['field']];
        } else {
            $this->crud_value[$val['field']] = '';
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
        foreach ($this->crud_field as $key => $val) {
            $this->crud_value[$val['field']] = $val['default_value'] ?? '';
        }
    }
}
