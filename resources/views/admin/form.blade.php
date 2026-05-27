@props(['table', 'columns', 'row', 'action', 'method'])

<x-admin-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">{{ $row ? 'Editar' : 'Crear' }} registro en {{ str_replace('_', ' ', $table) }}</h1>
            <p class="text-muted">Llena el formulario y guarda los cambios.</p>
        </div>
        <a href="{{ route('admin.table.index', ['table' => $table]) }}" class="btn btn-secondary">Volver a la lista</a>
    </div>

    <form action="{{ $action }}" method="post">
        @csrf
        @if($method === 'put')
            @method('put')
        @endif

        <div class="row g-3">
            @foreach($columns as $column)
                @php
                    $field = $column->Field;
                    $value = old($field, $row->{$field} ?? '');
                    $type = $column->Type;
                    $isReadonly = in_array($field, ['id', 'created_at', 'updated_at', 'deleted_at']);
                @endphp

                <div class="col-md-6">
                    <label class="form-label text-capitalize" for="{{ $field }}">{{ str_replace('_', ' ', $field) }}</label>
                    @if($field === 'created_at' || $field === 'updated_at')
                        <input type="text" class="form-control" id="{{ $field }}" value="{{ $value }}" readonly>
                    @elseif(\Illuminate\Support\Str::contains($type, ['text', 'longtext', 'json']))
                        <textarea class="form-control" name="{{ $field }}" id="{{ $field }}" rows="3" {{ $isReadonly ? 'readonly' : '' }}>{{ $value }}</textarea>
                    @elseif(\Illuminate\Support\Str::contains($type, ['date']))
                        <input type="date" class="form-control" name="{{ $field }}" id="{{ $field }}" value="{{ $value }}" {{ $isReadonly ? 'readonly' : '' }}>
                    @elseif(\Illuminate\Support\Str::contains($type, ['timestamp', 'datetime']))
                        <input type="datetime-local" class="form-control" name="{{ $field }}" id="{{ $field }}" value="{{ $value ? date('Y-m-d\TH:i', strtotime($value)) : '' }}" {{ $isReadonly ? 'readonly' : '' }}>
                    @elseif(\Illuminate\Support\Str::contains($type, ['tinyint(1)', 'boolean']))
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" value="1" name="{{ $field }}" id="{{ $field }}" {{ $value ? 'checked' : '' }} {{ $isReadonly ? 'disabled' : '' }}>
                            <label class="form-check-label" for="{{ $field }}">Activo</label>
                        </div>
                    @elseif(\Illuminate\Support\Str::contains($type, ['int', 'decimal', 'float']))
                        <input type="number" class="form-control" name="{{ $field }}" id="{{ $field }}" value="{{ $value }}" {{ $isReadonly ? 'readonly' : '' }}>
                    @else
                        <input type="text" class="form-control" name="{{ $field }}" id="{{ $field }}" value="{{ $value }}" {{ $isReadonly ? 'readonly' : '' }}>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Guardar</button>
        </div>
    </form>
</x-admin-layout>
