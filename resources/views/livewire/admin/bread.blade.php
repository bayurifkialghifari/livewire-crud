@push('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">BREAD</a>
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
                            <a class="btn btn-success float-end mt-3" href="{{ url('/admin/bread/create') }}">
                                <i class="bi bi-plus"></i>
                                Create
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-lg table-striped mb-0">
                                <thead>
                                    <tr>
                                        <th wire:click="changeOrder('name')" style="cursor: pointer">
                                            @if($orderBy == 'name')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Name
                                        </th>
                                        <th wire:click="changeOrder('url_slug')" style="cursor: pointer">
                                            @if($orderBy == 'url_slug')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            URL
                                        </th>
                                        <th wire:click="changeOrder('icon')" style="cursor: pointer">
                                            @if($orderBy == 'icon')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Icon
                                        </th>
                                        <th wire:click="changeOrder('table_name')" style="cursor: pointer">
                                            @if($orderBy == 'table_name')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Table
                                        </th>
                                        <th wire:click="changeOrder('primary_key')" style="cursor: pointer">
                                            @if($orderBy == 'primary_key')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Primary Key
                                        </th>
                                        <th wire:click="changeOrder('order_by')" style="cursor: pointer">
                                            @if($orderBy == 'order_by')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Order By
                                        </th>
                                        <th wire:click="changeOrder('order')" style="cursor: pointer">
                                            @if($orderBy == 'order')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Order By
                                        </th>
                                        <th wire:click="changeOrder('is_join')" style="cursor: pointer">
                                            @if($orderBy == 'is_join')
                                                @if($order == 'asc')
                                                    <i class="bi bi-caret-up-fill"></i>
                                                @else
                                                    <i class="bi bi-caret-down-fill"></i>
                                                @endif
                                            @endif
                                            Is Join
                                        </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $d)
                                        <tr>
                                            <td>{{ $d->name }}</td>
                                            <td>{{ $d->url_slug }}</td>
                                            <td>{{ $d->icon }}</td>
                                            <td>{{ $d->table_name }}</td>
                                            <td>{{ $d->primary_key }}</td>
                                            <td>{{ $d->order_by }}</td>
                                            <td>{{ $d->order }}</td>
                                            <td>{{ $d->is_join == 1 ? 'true' : 'false' }}</td>
                                            <td class="text-center">
                                                @if($d->is_join == 1)
                                                    <a class="btn btn-primary btn-sm"
                                                        href="{{ url('admin/bread/join/' . $d->id) }}">
                                                        <i class="bi bi-table"></i> Table Join
                                                    </a>
                                                @endif
                                                <a class="btn btn-primary btn-sm" href="{{ url('/admin/bread/update/' . $d->id) }}">
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
