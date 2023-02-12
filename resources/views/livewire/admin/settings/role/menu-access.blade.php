@push('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">Setting</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">
                Role {{ ucfirst($role->name) }}
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Menu Access
            </li>
        </ol>
    </nav>
@endpush

<section class="section">
    <div class="row" id="table-striped">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <select wire:model.lazy="paginate" class="form-control sm">
                                <option value="5">5 entries per page</option>
                                <option value="10">10 entries per page</option>
                                <option value="50">50 entries per page</option>
                                <option value="100">100 entries per page</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-md-4"></div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon">
                                    <i class="ti ti-search"></i>
                                </span>
                                <input wire:model="search" type="text" class="form-control sm"
                                    placeholder="Search......" autocomplete="off">
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12">
                            <button class="btn btn-success float-end mt-3" wire:click="isCreate" data-bs-toggle="modal"
                                data-bs-target="#modal-crud">
                                <i class="bi bi-plus"></i>
                                Create
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-lg table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th wire:click="changeOrder('roles.name')" style="cursor: pointer">
                                            @if ($orderBy == 'roles.name')
                                                @if ($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Role
                                        </th>
                                        <th wire:click="changeOrder('menus.name')" style="cursor: pointer">
                                            @if ($orderBy == 'menus.name')
                                                @if ($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Menu
                                        </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $d)
                                        <tr>
                                            <td>{{ $d->role }}</td>
                                            <td>{{ $d->menu }}</td>
                                            @php
                                                $sub_menus = $menu_model->find($d->menu_id)->sub_menus;
                                            @endphp
                                            <td class="text-center">
                                                @if (count($sub_menus))
                                                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                        data-bs-target="#modal-sub-menu"
                                                        wire:click="getSubMenu('{{ $d->id }}')">
                                                        <i class="bi bi-menu-down"></i> Sub Menu
                                                    </button>
                                                @endif
                                                <button class="btn btn-danger btn-sm"
                                                    wire:click="confirmDelete('{{ $d->id }}')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="text-center">
                                            <td colspan="10">Data empty</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            {{ $data->links('livewire.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal sub menu --}}
    <div wire:ignore.self class="modal fade" id="modal-sub-menu" tabindex="-1" aria-labelledby="modal-sub-menu-title"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form wire:submit.prevent="save_submenu">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-sub-menu-title">
                            Sub Menu
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            @foreach ($sub_menu as $sm)
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input class="form-check-input" wire:model="sub_menu_select" type="checkbox" value="{{ $sm->id }}"
                                            id="sub-check-{{ $sm->id }}">
                                        <label class="form-check-label" for="{{ $sm->id }}">
                                            {{ $sm->name }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

@livewire('modal-crud', [
    'title' => 'Menu Acess ' . ucfirst($role->name),
    'is_join' => false,
    'table_name' => 'role_has_menus',
    'primary_key' => 'id',
    'statusUpdate' => false,
    'insert_message' => 'Data created',
    'update_message' => 'Data updated',
    'crud_field' => [
        [
            'field' => 'role_id',
            'type' => 'select',
            'display_name' => 'Role',
            'placeholder' => null,
            'class_alt' => null,
            'id_alt' => null,
            'is_readonly' => true,
            'is_required' => true,
            'description' => null,
            'file_accept' => null,
            'default_value' => $role->id,
            'source' => 'roles',
            'source_id' => 'id',
            'source_value' => 'name',
        ],
        [
            'field' => 'menu_id',
            'type' => 'select',
            'display_name' => 'Menu',
            'placeholder' => null,
            'class_alt' => null,
            'id_alt' => null,
            'is_required' => true,
            'description' => null,
            'file_accept' => null,
            'default_value' => null,
            'source' => 'menus',
            'source_id' => 'id',
            'source_value' => 'name',
        ],
    ],
    'crud_rules' => [
        'crud_value.menu_id' => 'required',
    ],
    'crud_value' => [
        'id' => '',
        'role_id' => '',
        'menu_id' => '',
    ],
    'crud_rule_messages' => [
        'crud_value.menu_id.required' => 'Menu is required',
    ],
])
