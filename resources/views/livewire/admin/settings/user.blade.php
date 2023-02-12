@push('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">Setting</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Role
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
                                            @if($orderBy == 'roles.name')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Role
                                        </th>
                                        <th wire:click="changeOrder('users.name')" style="cursor: pointer">
                                            @if($orderBy == 'users.name')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Name
                                        </th>
                                        <th wire:click="changeOrder('users.email')" style="cursor: pointer">
                                            @if($orderBy == 'users.email')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Email
                                        </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $d)
                                        <tr>
                                            <td>{{ ucfirst($d->role[0]->name) }}</td>
                                            <td>{{ $d->name }}</td>
                                            <td>{{ $d->email }}</td>
                                            <td class="text-center">
                                                <a class="btn btn-primary btn-sm"
                                                    wire:click="isUpdate('{{ $d->id }}')" data-bs-toggle="modal"
                                                    data-bs-target="#modal-crud">
                                                    <i class="bi bi-pencil"></i> Update
                                                </a>
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
</section>

@livewire('modal-crud', [
    'title' => 'User',
    'is_join' => true,
    'join' => [
        [
            'origin_table' => 'users',
            'origin_key' => 'id',
            'foreign_table' => 'model_has_roles',
            'foreign_key' => 'model_id',
            'join_type' => 'left',
        ],
        [
            'origin_table' => 'model_has_roles',
            'origin_key' => 'role_id',
            'foreign_table' => 'roles',
            'foreign_key' => 'id',
            'join_type' => 'left',
        ],
    ],
    'table_name' => 'users',
    'primary_key' => 'id',
    'statusUpdate' => false,
    'insert_message' => 'Data created',
    'update_message' => 'Data updated',
    'crud_field' => [
        [
            'field' => 'roles-id',
            'foreign_table' => 'model_has_roles',
            'foreign_key' => 'model_id',
            'foreign_field' => 'role_id',
            'type' => 'select',
            'display_name' => 'Role',
            'placeholder' => null,
            'class_alt' => null,
            'id_alt' => null,
            'is_required' => true,
            'description' => null,
            'file_accept' => null,
            'default_value' => null,
            'source' => 'roles',
            'source_id' => 'id',
            'source_value' => 'name',
        ],
        [
            'field' => 'name',
            'type' => 'text',
            'display_name' => 'Name',
            'placeholder' => 'Name',
            'class_alt' => null,
            'id_alt' => null,
            'is_required' => true,
            'description' => null,
            'file_accept' => null,
            'default_value' => null,
            'source' => null,
            'source_id' => null,
            'source_value' => null,
        ],
        [
            'field' => 'email',
            'type' => 'email',
            'display_name' => 'Email',
            'placeholder' => 'Email',
            'class_alt' => null,
            'id_alt' => null,
            'is_required' => true,
            'description' => null,
            'file_accept' => null,
            'default_value' => null,
            'source' => null,
            'source_id' => null,
            'source_value' => null,
        ],
        [
            'field' => 'password',
            'type' => 'password',
            'display_name' => 'Password',
            'placeholder' => 'Password',
            'class_alt' => null,
            'id_alt' => null,
            'is_required' => true,
            'description' => null,
            'file_accept' => null,
            'default_value' => null,
            'source' => null,
            'source_id' => null,
            'source_value' => null,
        ],
    ],
    'crud_rules' => [
        'crud_value.roles-id' => 'required',
        'crud_value.name' => 'required',
        'crud_value.email' => 'required',
        'crud_value.password' => 'required',
    ],
    'crud_value' => [
        'id' => '',
        'roles-id' => '',
        'name' => '',
        'email' => '',
        'password' => '',
    ],
    'crud_rule_messages' => [
        'crud_value.roles-id.required' => 'Role is required',
        'crud_value.name.required' => 'Name is required',
        'crud_value.email.required' => 'Email is required',
        'crud_value.password.required' => 'Password is required',
    ],
])
