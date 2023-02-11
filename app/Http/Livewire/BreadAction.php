<?php

namespace App\Http\Livewire;

use App\Models\Bread;
use App\Models\BreadField;
// use Illuminate\Support\Facades\DB;
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
    public $search = '',
        $bread_id,
        $bread_slug,
        $bread_detail = [],
        $table_name,
        $fields = [],
        $paginate = 10,
        $orderBy,
        $order,
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
        $this->orderBy = $this->bread_detail->order_by;
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
        }
    }

    public function render()
    {
        // $data = ;
        return view('livewire.bread-action');
    }
}
