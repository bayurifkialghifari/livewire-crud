@push('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}/bread">BREAD {{ $bread->name }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Join
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
                                        <th wire:click="changeOrder('breads.name')" style="cursor: pointer">
                                            @if($orderBy == 'breads.name')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Bread
                                        </th>
                                        <th wire:click="changeOrder('origin_table')" style="cursor: pointer">
                                            @if($orderBy == 'origin_table')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Origin Table
                                        </th>
                                        <th wire:click="changeOrder('origin_key')" style="cursor: pointer">
                                            @if($orderBy == 'origin_key')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Origin Key
                                        </th>
                                        <th wire:click="changeOrder('foreign_table')" style="cursor: pointer">
                                            @if($orderBy == 'foreign_table')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Foreign Table
                                        </th>
                                        <th wire:click="changeOrder('foreign_key')" style="cursor: pointer">
                                            @if($orderBy == 'foreign_key')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Foreign Key
                                        </th>
                                        <th wire:click="changeOrder('join_type')" style="cursor: pointer">
                                            @if($orderBy == 'join_type')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Join Type
                                        </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $d)
                                        <tr>
                                            <td>{{ $d->bread }}</td>
                                            <td>{{ $d->origin_table }}</td>
                                            <td>{{ $d->origin_key }}</td>
                                            <td>{{ $d->foreign_table }}</td>
                                            <td>{{ $d->foreign_key }}</td>
                                            <td>{{ $d->join_type }}</td>
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

    <div wire:ignore.self class="modal fade" id="modal-crud" tabindex="-1" aria-labelledby="modal-crud-title"
    aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form wire:submit.prevent="save">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-crud-title">
                            @if ($title)
                                {{ $statusUpdate ? 'Update' : 'Create' }}
                            @endif
                            {{ $title ?? 'Modal Title' }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" wire:model="crud_value.id">
                        <input type="hidden" wire:model="crud_value.bread_id">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Origin table</label>
                                <select wire:model.lazy="crud_value.origin_table" wire:change="getDetailTable('origin')"
                                    class="form-control
                                        @error("values.origin_table")
                                            is-invalid
                                        @enderror
                                    ">
                                    <option value="">--Select table--</option>
                                    @foreach ($list_db_table as $origin)
                                        @php
                                            $val_origin = json_decode(json_encode($origin), true);
                                        @endphp
                                        @if(!in_array($val_origin['Tables_in_' . env('DB_DATABASE')], $exluded_table))
                                            <option value="{{ $val_origin['Tables_in_' . env('DB_DATABASE')] }}">{{ $val_origin['Tables_in_' . env('DB_DATABASE')] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error("crud_value.origin_table")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label>Origin key</label>
                                <select wire:model.lazy="crud_value.origin_key"
                                    class="form-control
                                        @error("values.origin_key")
                                            is-invalid
                                        @enderror
                                    ">
                                    <option value="">--Select origin key--</option>
                                    @foreach ($list_origin_field as $ok)
                                        <option value="{{ $ok->Field ?? $ok['Field'] }}">{{ $ok->Field ?? $ok['Field'] }}</option>
                                    @endforeach
                                </select>
                                @error("crud_value.origin_key")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label>Foreign table</label>
                                <select wire:model.lazy="crud_value.foreign_table" wire:change="getDetailTable('foreign')"
                                    class="form-control
                                        @error("values.foreign_table")
                                            is-invalid
                                        @enderror
                                    ">
                                    <option value="">--Select table--</option>
                                    @foreach ($list_db_table as $foreign)
                                        @php
                                            $val_foreign = json_decode(json_encode($foreign), true);
                                        @endphp
                                        @if(!in_array($val_foreign['Tables_in_' . env('DB_DATABASE')], $exluded_table))
                                            <option value="{{ $val_foreign['Tables_in_' . env('DB_DATABASE')] }}">{{ $val_foreign['Tables_in_' . env('DB_DATABASE')] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error("crud_value.foreign_table")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label>Foreign key</label>
                                <select wire:model.lazy="crud_value.foreign_key"
                                    class="form-control
                                        @error("values.foreign_key")
                                            is-invalid
                                        @enderror
                                    ">
                                    <option value="">--Select foreign key--</option>
                                    @foreach ($list_foreign_field as $fk)
                                        <option value="{{ $fk->Field ?? $fk['Field'] }}">{{ $fk->Field ?? $fk['Field'] }}</option>
                                    @endforeach
                                </select>
                                @error("crud_value.foreign_key")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12">
                                <label>Join Type</label>
                                <select wire:model.lazy="crud_value.join_type"
                                    class="form-control
                                        @error("values.join_type")
                                            is-invalid
                                        @enderror
                                    ">
                                    <option value="left">left</option>
                                    <option value="right">right</option>
                                </select>
                                @error("crud_value.join_type")
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
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
