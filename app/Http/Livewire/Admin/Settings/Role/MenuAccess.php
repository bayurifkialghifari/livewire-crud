<?php

namespace App\Http\Livewire\Admin\Settings\Role;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\RoleHasMenu;
use Spatie\Permission\Models\Role;
use App\Models\Menu;
use App\Models\SubMenu;

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
        $menu_id,
        $paginate = 10,
        $orderBy = 'menus.name',
        $order = 'asc',
        $sub_menu = [],
        $sub_menu_select = [],
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
        $menu_model = new Menu();
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

        $this->emitTo('navigation', 'refresh');
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

    // Get sub menu
    public function getSubMenu($id)
    {
        $sub_menu = SubMenu::where('menu_id', $id)->get();
        $this->sub_menu = $sub_menu;
        $this->sub_menu_select = [];

        // Set menu id
        $this->menu_id = $id;

        // Get selected sub menu
        $role_has_sub_menu = RoleHasMenu::where([
            'role_id' => $this->role_id,
            'menu_id' => $this->menu_id,
        ])
            ->whereNotNull('sub_menu_id')
            ->orWhere(function ($where) use ($sub_menu) {
                foreach ($sub_menu as $sm) {
                    $where->orWhere('sub_menu_id', $sm->id);
                }
            })
            ->get();

        // Set selected sub menu
        foreach ($role_has_sub_menu as $rhsm) {
            $this->sub_menu_select[] = $rhsm->sub_menu_id;
        }
    }

    // Save sub menu
    public function save_submenu()
    {
        // Get sub menu
        $sub_menu = SubMenu::where('menu_id', $this->menu_id)->get();

        // Delete if sub menu select 0
        if (count($this->sub_menu_select) == 0) {
            $role_has_menu = RoleHasMenu::where([
                'role_id' => $this->role_id,
                'menu_id' => $this->menu_id,
            ])
                ->whereNotNull('sub_menu_id')
                ->delete();
        }

        // Delete sub menu that not selected
        foreach ($sub_menu as $sm) {
            foreach ($this->sub_menu_select as $sms) {
                $found = false;

                // Check sub menu selected or not
                if ($sm->id == $sms) {
                    $found = true;

                    $role_has_menu = RoleHasMenu::where([
                        'role_id' => $this->role_id,
                        'menu_id' => $this->menu_id,
                        'sub_menu_id' => $sm->id,
                    ])->first();

                    // Create new data if not exist
                    if (!$role_has_menu) {
                        RoleHasMenu::create([
                            'role_id' => $this->role_id,
                            'menu_id' => $this->menu_id,
                            'sub_menu_id' => $sm->id,
                        ]);
                    }
                    break;
                }

                // If sub menu selected not found
                if (!$found) {
                    $role_has_menu = RoleHasMenu::where([
                        'role_id' => $this->role_id,
                        'menu_id' => $this->menu_id,
                        'sub_menu_id' => $sm->id,
                    ])->delete();
                }
            }
        }

        $this->emitTo('navigation', 'refresh');
        $this->emit('alert', 'Sub menu access saved');
        $this->emit('closeModal', 'modal-sub-menu');
    }
}
