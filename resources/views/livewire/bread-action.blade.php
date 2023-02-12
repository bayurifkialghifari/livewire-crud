@push('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            @foreach($active_menu as $k => $v)

                <li class="breadcrumb-item
                    @if($k === array_key_last($active_menu))
                        active
                    @endif">
                    <a href="#">{{$v}}</a>
                </li>
            @endforeach
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
                                        @foreach($fields as $th)
                                            {{-- Is browse --}}
                                            @if($th->is_browse == 1)
                                                @php
                                                    $fieldOrder = $th->foreign_table
                                                        ? $th->foreign_table . '.' . $th->foreign_field
                                                        : $table_name . '.' . $th->field;
                                                @endphp
                                                <th wire:click="changeOrder('{{ $fieldOrder }}')" style="cursor: pointer">
                                                    @if($orderBy == $fieldOrder)
                                                        @if($order == 'asc')
                                                            <i class="bi bi-caret-up-fill"></i>
                                                        @else
                                                            <i class="bi bi-caret-down-fill"></i>
                                                        @endif
                                                    @endif
                                                    {{ $th->display_name }}
                                                </th>
                                            @endif
                                        @endforeach
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $d)
                                        <tr>
                                            @foreach($fields as $th)
                                                @if($th->is_browse == 1)
                                                    @php
                                                        $dp = $th->field;

                                                        // If foreign table
                                                        if($th->foreign_table) {
                                                            $dp = $th->foreign_table . $th->foreign_field;
                                                        }
                                                    @endphp
                                                    <td>{{ $d->{$dp} }}</td>
                                                @endif
                                            @endforeach
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
    'title' => $bread_detail->name,
    'is_join' => $bread_detail->is_join,
    'join' => $crud_join,
    'table_name' => $table_name,
    'primary_key' => $bread_detail->primary_key,
    'statusUpdate' => false,
    'insert_message' => 'Data created',
    'update_message' => 'Data updated',
    'crud_field' => $crud_field,
    // 'crud_rules' => [
    //     'crud_value.name' => 'required',
    //     'crud_value.url' => 'required',
    //     'crud_value.icon' => 'required',
    //     'crud_value.index' => 'required',
    // ],
    'crud_value' => $crud_value,
    // 'crud_rule_messages' => [
    //     'crud_value.name.required' => 'Name is required',
    //     'crud_value.url.required' => 'Url is required',
    //     'crud_value.icon.required' => 'Icon is required',
    //     'crud_value.index.required' => 'Index is required',
    // ],
])
