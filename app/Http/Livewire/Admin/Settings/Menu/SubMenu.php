<?php

namespace App\Http\Livewire\Admin\Settings\Menu;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Route;
use App\Models\SubMenu as SubMenus;
use App\Models\Menu;

class SubMenu extends Component
{
    use WithPagination;

    protected $listeners = [
        'delete' => 'destroy',
        'refresh' => '$refresh',
        'isUpdate',
        'isCreate',
    ];
    public $searchable = ['menus.name', 'sub_menus.name', 'sub_menus.url', 'sub_menus.class', 'sub_menus.icon'];
    public $search = '',
        $menu_id,
        $paginate = 10,
        $orderBy = 'sub_menus.index',
        $order = 'asc',
        $update = false;

    // Get parameter from route
    public function mount($id)
    {
        $this->menu_id = $id;
    }

    // Render page
    public function render()
    {
        // Active menu
        $active_menu = ['Setting', 'Sub Menu'];

        // Get data
        $menu_id = $this->menu_id;
        $menu = Menu::find($menu_id)->first();
        $sql = SubMenus::leftJoin('menus', 'menus.id', '=', 'sub_menus.menu_id')
            ->where('sub_menus.menu_id', $menu_id)
            ->orderBy($this->orderBy, $this->order)
            ->select('sub_menus.*', 'menus.name as parent')
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

        return view('livewire.admin.settings.menu.sub-menu', compact('data', 'menu', 'menu_id'))->layoutData(compact('active_menu'));
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
        $exe = SubMenus::find($id);
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
