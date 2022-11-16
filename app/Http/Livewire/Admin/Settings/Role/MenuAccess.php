<?php

namespace App\Http\Livewire\Admin\Settings\Role;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Route;
use App\Models\RoleHasMenu;
use Spatie\Permission\Models\Role;
use App\Models\Menu;

class MenuAccess extends Component
{
    use WithPagination;

    protected $listeners = [
        'delete' => 'destroy',
        'refresh' => '$refresh',
        'isUpdate',
        'isCreate',
    ];
    public $searchable = ['menus.name', 'sub_menus.name', 'roles.name'];
    public $search = '',
        $role_id,
        $paginate = 10,
        $orderBy = 'menus.name',
        $order = 'asc',
        $update = false;

    // Get parameter from route
    public function mount($id)
    {
        $this->role_id = $id;
    }

    // Render page
    public function render()
    {
        // Active menu
        $active_menu = ['Setting', 'Menu Access'];

        // Get data
        $role_id = $this->role_id;
        $role = Role::find($role_id);
        $menu = Menu::all();
        $menu_model = new Menu;
        $sql = RoleHasMenu::leftJoin('menus', 'role_has_menus.menu_id', '=', 'menus.id')
            ->leftJoin('roles', 'role_has_menus.role_id', '=', 'roles.id')
            ->where('role_has_menus.role_id', $role_id)
            ->whereNull('role_has_menus.sub_menu_id')
            ->orderBy($this->orderBy, $this->order)
            ->select('role_has_menus.*', 'menus.name as menu', 'roles.name as role')
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

        return view('livewire.admin.settings.role.menu-access', compact('data', 'menu', 'menu_model', 'role', 'role_id'))->layoutData(compact('active_menu'));
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
        $exe = RoleHasMenu::find($id);
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
