<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Bread as Breads;

class Bread extends Component
{
    use WithPagination;

    protected $listeners = [
        'delete' => 'destroy',
        'refresh' => '$refresh',
        'isUpdate',
        'isCreate',
    ];
    public $searchable = ['name', 'url_slug', 'icon', 'table_name', 'primary_key', 'order_by', 'order', 'is_join'];
    public $search = '',
        $paginate = 10,
        $orderBy = 'name',
        $order = 'asc',
        $update = false;

    // Render page
    public function render()
    {
        // Active menu
        $active_menu = ['BREAD'];

        // Get data
        $sql = Breads::orderBy($this->orderBy, $this->order)->latest();
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

        return view('livewire.admin.bread', compact('data'))->layoutData(compact('active_menu'));
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
        $exe = Breads::find($id);
        $exe->delete();

        $this->emit('alert', 'Delete data success');
    }

    // Confirm delete
    public function confirmDelete($id)
    {
        $this->emit('confirm', $id);
    }
}
