@push('breadcrumb')
    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ url('admin') }}">Dashboard</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ url('admin/bread') }}">BREAD</a>
            </li>
            <li class="breadcrumb-item">
                <a href="#">Create</a>
            </li>
        </ol>
    </nav>
@endpush

<section class="section">
    <form wire:submit.prevent="save">
        <div class="row container">
            <div class="col-md-12">
                <div class="form-group">
                    <label>Name</label>
                    @error("values.name")
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <input
                        type="text"
                        class="form-control
                        @error("values.name")
                            is-invalid
                        @enderror"
                        wire:model="values.name"
                        wire:keyup="getSingularPlural"
                        placeholder="Name"
                    />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>URL</label>
                    @error("values.url_slug")
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <input
                        type="text"
                        class="form-control
                        @error("values.url_slug")
                            is-invalid
                        @enderror"
                        wire:model="values.url_slug"
                        placeholder="Url"
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Display Name Singular</label>
                    @error("values.display_name_singular")
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <input
                        type="text"
                        class="form-control
                        @error("values.display_name_singular")
                            is-invalid
                        @enderror"
                        wire:model.lazy="values.display_name_singular"
                        placeholder="Singular"
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Display Name Plural</label>
                    @error("values.display_name_plural")
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <input
                        type="text"
                        class="form-control
                        @error("values.display_name_plural")
                            is-invalid
                        @enderror"
                        wire:model.lazy="values.display_name_plural"
                        placeholder="Plural"
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Table</label>
                    @error("values.table_name")
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <select wire:model.lazy="values.table_name" class="form-control
                        @error("values.table_name")
                            is-invalid
                        @enderror"
                        wire:change="getDetailTable"
                    >
                        <option value="">--Select Table--</option>
                        @foreach ($list_db_table as $table)
                            @php
                                $val = json_decode(json_encode($table), true);
                            @endphp
                            @if(!in_array($val['Tables_in_' . env('DB_DATABASE')], $exluded_table))
                            <option value="{{ $val['Tables_in_' . env('DB_DATABASE')] }}">{{ $val['Tables_in_' . env('DB_DATABASE')] }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Primary key</label>
                    @error("values.primary_key")
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <input wire:model.lazy="values.primary_key" class="form-control
                        @error("values.primary_key")
                            is-invalid
                        @enderror"
                        placeholder="Primary key"
                        readonly
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Order By Field</label>
                    @error("values.order_by")
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <select wire:model.lazy="values.order_by" class="form-control
                        @error("values.order_by")
                            is-invalid
                        @enderror"
                    >
                        <option value="">--Select Column--</option>
                        @foreach ($columns as $column)
                            <option value="{{ $column->Field ?? $column['Field'] }}">{{ $column->Field ?? $column['Field'] }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Order By</label>
                    @error("values.order")
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                    <select wire:model.lazy="values.order" class="form-control
                        @error("values.order")
                            is-invalid
                        @enderror"
                    >
                        <option value="ASC">ASC</option>
                        <option value="DESC">DESC</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Is Join</label>
                    <select wire:model.lazy="values.is_join" class="form-control
                        @error("values.is_join")
                            is-invalid
                        @enderror"
                    >
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Active Menu</label>
                    <input wire:model.lazy="values.active_menu" class="form-control
                        @error("values.active_menu")
                            is-invalid
                        @enderror"
                        placeholder="Active menu ex (Setting,Role) => Setting parent menu and Role child menu"
                    />
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Custom Button</label>
                    <textarea wire:model.lazy="values.custom_button" class="form-control
                        @error("values.custom_button")
                            is-invalid
                        @enderror"
                        placeholder="Custom Button ex (<button>Test</button> <a>Link</a>)"
                    ></textarea>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label>Description</label>
                    <textarea wire:model.lazy="values.description" class="form-control
                        @error("values.description")
                            is-invalid
                        @enderror"
                        placeholder="Description"
                    ></textarea>
                </div>
            </div>
            <div class="col-12 d-flex justify-content-end">
                <a href="{{ url('admin/bread') }}" class="btn btn btn-danger me-1 mb-1">
                    Back
                </a>
                <button type="submit" class="btn btn-primary me-1 mb-1">
                    Save
                </button>
            </div>
        </div>
    </form>
</section>
