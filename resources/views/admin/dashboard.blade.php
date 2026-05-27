@props(['tables'])

<x-admin-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1>Tablas del sistema</h1>
            <p class="text-muted">Selecciona una tabla para administrar registros.</p>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-3">
        @foreach($tables as $table)
            <div class="col">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title text-capitalize">{{ str_replace('_', ' ', $table) }}</h5>
                        <p class="card-text">Gestiona la tabla <code>{{ $table }}</code> con búsqueda, filtros y edición.</p>
                        <a href="{{ route('admin.table.index', ['table' => $table]) }}" class="btn btn-primary">Abrir</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-admin-layout>
