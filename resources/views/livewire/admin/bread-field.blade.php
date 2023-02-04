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
                            <button class="btn btn-warning float-end mt-3">
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
                                            <td>{{ $d->field }}</td>
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
                                                <button class="btn btn-primary btn-sm">
                                                    <i class="bi bi-plus"></i> Aditional Rules
                                                </button>
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
                                                <button class="btn btn-primary btn-sm">
                                                    <i class="bi bi-plus"></i> Aditional field setting
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <button class="btn btn-success float-end mb-5">
                                <i class="bi bi-save"></i>
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
