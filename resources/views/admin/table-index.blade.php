@props(['table', 'columns', 'rows', 'primaryKey', 'filters', 'search'])

<x-admin-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3">{{ str_replace('_', ' ', $table) }}</h1>
            <p class="text-muted">Lista de registros con búsqueda global y filtros.</p>
        </div>
        <a href="{{ route('admin.table.create', ['table' => $table]) }}" class="btn btn-success">Nuevo registro</a>
    </div>

    <form class="row g-3 mb-4" method="get" action="{{ route('admin.table.index', ['table' => $table]) }}">
        <div class="col-md-4">
            <label class="form-label">Buscar</label>
            <input type="search" name="q" value="{{ old('q', $search) }}" class="form-control" placeholder="Buscar texto...">
        </div>
        @foreach($filters as $filter)
            <div class="col-md-2">
                <label class="form-label">{{ str_replace('_', ' ', $filter->Field) }}</label>
                <input type="text" name="filter[{{ $filter->Field }}]" value="{{ request()->input('filter.' . $filter->Field) }}" class="form-control">
            </div>
        @endforeach
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle">
            <thead class="table-light">
                <tr>
                    @foreach($columns as $column)
                        <th>{{ $column->Field }}</th>
                    @endforeach
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rows as $row)
                    <tr>
                        @foreach($columns as $column)
                            <td>{{ $row->{$column->Field} }}</td>
                        @endforeach
                        <td class="text-end">
                            @php
                                $primaryValues = [];
                                foreach ($primaryKey as $pk) {
                                    $primaryValues[$pk] = $row->{$pk};
                                }
                                $encodedKey = rtrim(strtr(base64_encode(json_encode($primaryValues)), '+/', '-_'), '=');
                            @endphp
                            <a href="{{ route('admin.table.edit', ['table' => $table, 'key' => $encodedKey]) }}" class="btn btn-sm btn-primary">Editar</a>
                                <form action="{{ route('admin.table.destroy', ['table' => $table, 'key' => $encodedKey]) }}" method="post" class="d-inline-block" onsubmit="return confirm('Eliminar este registro?');">
                                @csrf
                                @method('delete')
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($columns) + 1 }}" class="text-center">No se encontraron registros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $rows->links() }}
</x-admin-layout>
