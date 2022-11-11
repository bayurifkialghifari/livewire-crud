<?php

namespace App\Http\Livewire\Admin\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role as Roles;

class Role extends Component
{
    use WithPagination;

    protected $listeners = [
        'delete' => 'destroy',
        'isUpdate',
        'isCreate',
    ];
    public $searchable = ['name'];
    public $search = '',
        $paginate = 10,
        $orderBy = 'name',
        $order = 'desc',
        $statusUpdate = false;

    // Render page
    public function render()
    {
        // Active menu
        $active_menu = ['Setting', 'Role'];

        // Get data
        $sql = Roles::orderBy($this->orderBy, $this->order)->latest();
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

        return view('livewire.admin.settings.role', compact('data'))->layoutData(compact('active_menu'));
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
        $exe = Roles::find($id);
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
        $this->statusUpdate = true;

        $this->emitTo('modelCrud', 'getDetail', $id);
    }

    // Set status update false
    public function isCreate()
    {
        $this->statusUpdate = false;
    }
}
