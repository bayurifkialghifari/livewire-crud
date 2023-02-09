@push('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}/bread">BREAD {{ ucwords($bread->table_name) }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Fields
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
                        <div class="col-lg-12 col-md-12">
                            <button class="btn btn-warning float-end mt-3" wire:click="isCreate" data-bs-toggle="modal" data-bs-target="#modal-crud">
                                <i class="bi bi-plus"></i>
                                Add new field
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive-lg">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Table</th>
                                        <th>Field</th>
                                        <th>Visibility</th>
                                        <th>Rules</th>
                                        <th>Input Type</th>
                                        <th>Display Name</th>
                                        <th>Place Holder</th>
                                        <th>Desription</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                        <tr>
                                            <td>{{ $d->foreign_table ?? $d->bread  }}</td>
                                            <td>{{ $d->field ?? $d->bread_fields }}</td>
                                            {{-- Visibility --}}
                                            <td>
                                                <input type="checkbox" wire:model="crud_value.is_searchable.{{ $d->id }}" class="form-check-input" />Browse<br>
                                                <input type="checkbox" wire:model="crud_value.is_browse.{{ $d->id }}" class="form-check-input" />Read<br>
                                                <input type="checkbox" wire:model="crud_value.is_add.{{ $d->id }}" class="form-check-input" />Insert<br>
                                                <input type="checkbox" wire:model="crud_value.is_edit.{{ $d->id }}" class="form-check-input" />Update<br>
                                            </td>
                                            {{-- Rules --}}
                                            <td>
                                                <input type="checkbox" wire:model="crud_value.is_readonly.{{ $d->id }}" class="form-check-input" />Readonly<br>
                                                <input type="checkbox" wire:model="crud_value.is_required.{{ $d->id }}" class="form-check-input" />Required<br>
                                                {{-- <button class="btn btn-primary btn-sm">
                                                    <i class="bi bi-plus"></i> Aditional Rules
                                                </button> --}}
                                            </td>
                                            {{-- Input type --}}
                                            <td>
                                                <select class="form-control" wire:model="crud_value.type.{{ $d->id }}">
                                                    <option value="text">Text</option>
                                                    <option value="number">Number</option>
                                                    <option value="email">Email</option>
                                                    <option value="password">Password</option>
                                                    <option value="date">Date</option>
                                                    <option value="time">Time</option>
                                                    <option value="file">File</option>
                                                    <option value="select">Select</option>
                                                    <option value="checkbox">Checkbox</option>
                                                    <option value="radio">Radio</option>
                                                </select>
                                                {{-- If file --}}
                                                @if($crud_value['type'][$d->id] == 'file')
                                                    <label>File Accept</label>
                                                    <br>
                                                    <input type="text" placeholder="ex: image/*" wire:model="crud_value.file_accept.{{ $d->id }}">
                                                @endif
                                                {{-- If select --}}
                                                @if($crud_value['type'][$d->id] == 'select')
                                                    <label>Sources</label>
                                                    <br>
                                                    <input type="text" placeholder="Table name or php array" wire:model="crud_value.source.{{ $d->id }}">
                                                    <br>
                                                    <label>Sources Label</label>
                                                    <br>
                                                    <input type="text" placeholder="Value source" wire:model="crud_value.source_value.{{ $d->id }}">
                                                    <br>
                                                    <label>Sources Value</label>
                                                    <br>
                                                    <input type="text" placeholder="Value source" wire:model="crud_value.source_id.{{ $d->id }}">

                                                @endif
                                            </td>
                                            {{-- Display --}}
                                            <td>
                                                <input type="text" wire:model="crud_value.display_name.{{ $d->id }}">
                                            </td>
                                            {{-- Placeholder --}}
                                            <td>
                                                <input type="text" placeholder="Input placeholder" wire:model="crud_value.placeholder.{{ $d->id }}">
                                            </td>
                                            {{-- Desc --}}
                                            <td>
                                                <label>Value</label>
                                                <br>
                                                <input type="text" placeholder="Description value" wire:model="crud_value.description.{{ $d->id }}">
                                                <br>
                                                <label>Class</label>
                                                <br>
                                                <input type="text" placeholder="Description class" wire:model="crud_value.description_class.{{ $d->id }}">
                                                <br>
                                                <br>
                                                {{-- <button class="btn btn-primary btn-sm">
                                                    <i class="bi bi-plus"></i> Aditional field setting
                                                </button> --}}
                                                <button class="btn btn-danger btn-sm" wire:click="confirmDelete('{{ $d->id }}')">
                                                    <i class="bi bi-trash"></i> Delete
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button class="btn btn-success float-end mb-5" wire:click="save">
                                <i class="bi bi-save"></i>
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="modal-crud" tabindex="-1" aria-labelledby="modal-crud-title"
    aria-hidden="true">
        <div class="modal-dialog modal-md">
            <form wire:submit.prevent="createNewField">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-crud-title">
                            Create New Field {{ ucwords($bread->table_name) }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" wire:model="crud_new.bread_id">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Is Foreign</label>
                                <select wire:model="is_foreign" wire:change="updateForeign" class="form-control">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                        <div class="{{ $is_foreign ? 'd-block' : 'd-none'}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Foreign table</label>
                                    <select wire:model="crud_new.foreign_table" wire:change="getDetailTable()" class="form-control">
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
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Foreign Key</label>
                                    <select wire:model.lazy="crud_new.foreign_key" class="form-control">
                                        <option value="">--Select field--</option>
                                        @foreach ($list_foreign_field as $ff)
                                            @php
                                                $v_ff = json_decode(json_encode($ff), true);
                                            @endphp
                                            <option value="{{ $v_ff->Field ?? $v_ff['Field'] }}">{{ $v_ff->Field ?? $v_ff['Field'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Foreign Feild <b>(Value location save)</b></label>
                                    <select wire:model.lazy="crud_new.foreign_field" class="form-control">
                                        <option value="">--Select field--</option>
                                        @foreach ($list_foreign_field as $ff)
                                            @php
                                                $v_ff = json_decode(json_encode($ff), true);
                                            @endphp
                                            <option value="{{ $v_ff->Field ?? $v_ff['Field'] }}">{{ $v_ff->Field ?? $v_ff['Field'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="{{ $is_foreign ? 'd-none' : 'd-block'}}">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Feild</b></label>
                                    <select wire:model.lazy="crud_new.field" class="form-control">
                                        <option value="">--Select field--</option>
                                        @foreach ($list_origin_field as $origin)
                                            @php
                                                $v_origin = json_decode(json_encode($origin), true);
                                            @endphp
                                            <option value="{{ $v_origin->Field ?? $v_origin['Field'] }}">{{ $v_origin->Field ?? $v_origin['Field'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
