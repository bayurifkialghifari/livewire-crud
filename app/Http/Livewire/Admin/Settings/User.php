<?php

namespace App\Http\Livewire\Admin\Settings;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User as Users;

class User extends Component
{
    use WithPagination;

    protected $listeners = [
        'delete' => 'destroy',
        'refresh' => '$refresh',
        'isUpdate',
        'isCreate',
    ];
    public $searchable = ['roles.name', 'users.name', 'users.email'];
    public $search = '',
        $paginate = 10,
        $orderBy = 'roles.name',
        $order = 'asc',
        $update = false;

    // Render page
    public function render()
    {
        // Active menu
        $active_menu = ['Setting', 'Menu'];

        // Get data
        $sql = Users::leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->orderBy($this->orderBy, $this->order)
            ->select('users.*')
            ->latest();
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

        return view('livewire.admin.settings.user', compact('data'))->layoutData(compact('active_menu'));
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
        $exe = Users::find($id);
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
