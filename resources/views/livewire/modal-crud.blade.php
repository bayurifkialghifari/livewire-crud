<div wire:ignore.self class="modal fade" id="{{ $modal_id ?? 'modal-crud' }}" tabindex="-1" aria-labelledby="modal-crud-title"
    aria-hidden="true">
    <div class="modal-dialog modal-{{ $modal_size }} {{ $modal_class }}">
        <form @if ($is_submit) wire:submit.prevent="save" @endif>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-crud-title">
                        @if ($title)
                            {{ $statusUpdate ? 'Update' : 'Create' }}
                        @endif
                        {{ $title ?? 'Modal Title' }}
                    </h5>
                    @if ($button_close)
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    @endif
                </div>
                <div class="modal-body">
                    @if ($crud_field)
                        <input type="hidden" wire:model="crud_value.{{ $primary_key }}">
                        <div class="row">
                            @foreach ($crud_field as $cf)
                                @php
                                    $cf = (object) $cf;
                                @endphp
                                <div class="col-md-12">
                                    <label {{ $cf->id_alt ? "for='#${$cf->id_alt}'" : '' }}>
                                        @if ($cf->type != 'hidden')
                                            {{ $cf->display_name ?? $cf->field }}
                                        @endif
                                    </label>
                                    @if ($cf->description)
                                        <br />
                                        <span class="{{ $cf->description_class ?? '' }}">{{ $cf->description }}</span>
                                    @endif
                                    @if ($cf->type == 'textarea')
                                        <textarea
                                            class="
                                                    form-control {{ $cf->class_alt ?? '' }}
                                                    @error("crud_value.$cf->field")
                                                        is-invalid
                                                    @enderror
                                            "
                                            placeholder="{{ $cf->placeholder ?? '' }}" {{-- {{ $cf->is_required ? 'required' : '' }} --}}
                                            @if(isset($cf->is_readonly))
                                                {{ $cf->is_readonly ? 'readonly' : '' }}
                                            @endif
                                            {{ $cf->id_alt ? "id='${$cf->id_alt}'" : '' }} wire:model.lazy="crud_value.{{ $cf->field }}">
                                        </textarea>
                                    @elseif($cf->type == 'select')
                                        <select
                                            class="
                                                form-control {{ $cf->class_alt ?? '' }}
                                                @error("crud_value.$cf->field")
                                                    is-invalid
                                                @enderror
                                            "
                                            {{ $cf->id_alt ? "id='{$cf->id_alt}'" : '' }}
                                            @if(isset($cf->is_readonly))
                                                {{ $cf->is_readonly ? 'disabled' : '' }}
                                            @endif
                                            wire:model.lazy="crud_value.{{ $cf->field }}">
                                            <option value="">--Select {{ $cf->display_name ?? $cf->field }}--</option>
                                            {{-- Get value select --}}
                                            @if($cf->source)
                                                @php
                                                    $source = \Illuminate\Support\Facades\DB::table($cf->source)->get();
                                                @endphp
                                                @foreach ($source as $src)
                                                    <option value="{{ $src->{$cf->source_id} }}">{{ $src->{$cf->source_value} }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    @else
                                        <input type="{{ $cf->type ?? 'text' }}"
                                            class="
                                                form-control {{ $cf->class_alt ?? '' }}
                                                @error("crud_value.$cf->field")
                                                    is-invalid
                                                @enderror
                                            "
                                            placeholder="{{ $cf->placeholder ?? '' }}" {{-- {{ $cf->is_required ? 'required' : '' }} --}}
                                            @if(isset($cf->is_readonly))
                                                {{ $cf->is_readonly ? 'readonly' : '' }}
                                            @endif
                                            {{ $cf->id_alt ? "id='{$cf->id_alt}'" : '' }}
                                            {{ $cf->file_accept ? "accept='{$cf->file_accept}'" : '' }}
                                            wire:model.lazy="crud_value.{{ $cf->field }}">
                                    @endif
                                    @error("crud_value.$cf->field")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    @if ($button)
                        {!! $button !!}
                    @else
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
