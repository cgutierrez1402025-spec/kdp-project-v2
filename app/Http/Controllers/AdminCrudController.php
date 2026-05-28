<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminCrudController extends Controller
{
    public function dashboard()
    {
        $tables = collect($this->getTables())
            ->filter(fn (string $table) => $table !== 'migrations')
            ->sort()
            ->values();

        return view('admin.dashboard', [
            'tables' => $tables,
        ]);
    }

    public function index(Request $request, string $table)
    {
        $this->ensureTableExists($table);

        $columns = $this->getColumns($table);
        $query = DB::table($table);

        if ($request->filled('q')) {
            $search = $request->input('q');
            $query->where(function ($builder) use ($columns, $search) {
                foreach ($columns as $column) {
                    if ($this->isSearchableColumn($column)) {
                        $builder->orWhere($column->Field, 'like', "%{$search}%");
                    }
                }
            });
        }

        foreach ($request->input('filter', []) as $field => $value) {
            if ($value !== null && $value !== '') {
                $query->where($field, $value);
            }
        }

        $rows = $query->paginate(15)->withQueryString();
        $primaryKey = $this->getPrimaryKey($table);
        $filters = $this->getFilterableColumns($columns);

        return view('admin.table-index', [
            'table' => $table,
            'columns' => $columns,
            'rows' => $rows,
            'primaryKey' => $primaryKey,
            'filters' => $filters,
            'search' => $request->input('q', ''),
        ]);
    }

    public function create(string $table)
    {
        $this->ensureTableExists($table);
        $columns = $this->getColumns($table);

        return view('admin.form', [
            'table' => $table,
            'columns' => $columns,
            'row' => null,
            'action' => route('admin.table.store', ['table' => $table]),
            'method' => 'post',
        ]);
    }

    public function store(Request $request, string $table)
    {
        $this->ensureTableExists($table);
        $columns = $this->getColumns($table);
        
        // Validar datos antes de guardar
        $rules = $this->buildValidationRules($table, $columns);
        $validated = $request->validate($rules);
        
        $data = $this->normalizeRequestData($request, $columns);

        $id = DB::table($table)->insertGetId($data);

        return redirect()->route('admin.table.edit', ['table' => $table, 'key' => $this->encodeKey(['id' => $id])])
            ->with('success', "Registro creado en {$table}.");
    }

    public function edit(string $table, string $key)
    {
        $this->ensureTableExists($table);
        $columns = $this->getColumns($table);
        $primary = $this->decodeKey($key);

        $row = DB::table($table)->where($primary)->first();
        abort_if(!$row, 404);

        return view('admin.form', [
            'table' => $table,
            'columns' => $columns,
            'row' => $row,
            'action' => route('admin.table.update', ['table' => $table, 'key' => $key]),
            'method' => 'put',
        ]);
    }

    public function update(Request $request, string $table, string $key)
    {
        $this->ensureTableExists($table);
        $columns = $this->getColumns($table);
        $primary = $this->decodeKey($key);
        
        // Validar datos antes de actualizar
        $rules = $this->buildValidationRules($table, $columns);
        $validated = $request->validate($rules);

        $data = $this->normalizeRequestData($request, $columns);
        DB::table($table)->where($primary)->update($data);

        return redirect()->route('admin.table.edit', ['table' => $table, 'key' => $key])
            ->with('success', "Registro actualizado en {$table}.");
    }

    public function destroy(string $table, string $key)
    {
        $this->ensureTableExists($table);
        $primary = $this->decodeKey($key);

        DB::table($table)->where($primary)->delete();

        return redirect()->route('admin.table.index', ['table' => $table])
            ->with('success', "Registro eliminado en {$table}.");
    }

    protected function ensureTableExists(string $table): void
    {
        abort_if(!Schema::hasTable($table), 404, "Tabla {$table} no encontrada.");
    }

    protected function getTables(): array
    {
        if (DB::getDriverName() === 'sqlite') {
            return collect(DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'"))
                ->map(fn ($row) => $row->name)
                ->all();
        }

        return collect(DB::select('SHOW TABLES'))
            ->map(fn ($row) => array_values((array) $row)[0])
            ->all();
    }

    protected function getColumns(string $table): array
    {
        if (DB::getDriverName() === 'sqlite') {
            return collect(DB::select("PRAGMA table_info('{$table}')"))
                ->map(fn ($column) => (object) [
                    'Field' => $column->name,
                    'Type' => $column->type,
                    'Null' => $column->notnull === 0 ? 'YES' : 'NO',
                    'Key' => $column->pk ? 'PRI' : '',
                    'Column_name' => $column->name,
                ])
                ->all();
        }

        return DB::select("SHOW FULL COLUMNS FROM `{$table}`");
    }

    protected function getPrimaryKey(string $table): array
    {
        if (DB::getDriverName() === 'sqlite') {
            $columns = collect(DB::select("PRAGMA table_info('{$table}')"));
            $keys = $columns->filter(fn ($column) => $column->pk > 0)->pluck('name')->all();
            return !empty($keys) ? $keys : ['id'];
        }

        $keys = DB::select("SHOW KEYS FROM `{$table}` WHERE Key_name = 'PRIMARY'");
        if (empty($keys)) {
            return ['id'];
        }

        return array_map(fn ($row) => $row->Column_name, $keys);
    }

    protected function normalizeRequestData(Request $request, array $columns): array
    {
        $data = [];
        foreach ($columns as $column) {
            if (in_array($column->Field, ['id', 'created_at', 'updated_at', 'deleted_at'], true)) {
                continue;
            }
            $value = $request->input($column->Field);
            if ($value === null) {
                if ($column->Null === 'YES') {
                    $data[$column->Field] = null;
                }
                continue;
            }
            if ($this->isBooleanColumn($column)) {
                $data[$column->Field] = $request->has($column->Field) ? 1 : 0;
            } else {
                $data[$column->Field] = $value;
            }
        }

        return $data;
    }

    protected function isBooleanColumn($column): bool
    {
        return Str::contains(Str::lower($column->Type), 'tinyint(1)') || Str::contains(Str::lower($column->Type), 'boolean');
    }

    protected function isSearchableColumn($column): bool
    {
        $type = Str::lower($column->Type);
        return Str::contains($type, ['char', 'text', 'varchar']);
    }

    protected function getFilterableColumns(array $columns): array
    {
        return array_values(array_filter($columns, fn ($column) => $this->isSearchableColumn($column) && !in_array($column->Field, ['created_at', 'updated_at', 'deleted_at'], true)));
    }

    /**
     * Construir reglas de validación basadas en la estructura de la tabla
     * 
     * @param string $table Nombre de la tabla
     * @param array $columns Columnas obtenidas de getColumns()
     * @return array Reglas de validación para Laravel
     */
    protected function buildValidationRules(string $table, array $columns): array
    {
        $rules = [];
        $excludeFields = ['id', 'created_at', 'updated_at', 'deleted_at'];
        $primaryKeys = $this->getPrimaryKey($table);
        
        foreach ($columns as $column) {
            // Excluir campos de sistema y claves primarias
            if (in_array($column->Field, $excludeFields, true) || in_array($column->Field, $primaryKeys, true)) {
                continue;
            }

            $fieldRules = [];
            $type = Str::lower($column->Type);

            // Regla de requerimiento
            if ($column->Null === 'NO') {
                $fieldRules[] = 'required';
            } else {
                $fieldRules[] = 'nullable';
            }

            // Reglas específicas por tipo de dato
            if ($this->isBooleanColumn($column)) {
                $fieldRules[] = 'boolean';
            } elseif (Str::contains($type, ['json'])) {
                $fieldRules[] = 'json';
            } elseif (Str::contains($type, ['longtext', 'mediumtext', 'text'])) {
                $fieldRules[] = 'string';
            } elseif (Str::contains($type, 'varchar')) {
                // Extraer longitud de varchar(n)
                if (preg_match('/varchar\((\d+)\)/', $column->Type, $matches)) {
                    $length = (int) $matches[1];
                    $fieldRules[] = "string|max:{$length}";
                } else {
                    $fieldRules[] = 'string';
                }
            } elseif (Str::contains($type, 'char')) {
                // Extraer longitud de char(n)
                if (preg_match('/char\((\d+)\)/', $column->Type, $matches)) {
                    $length = (int) $matches[1];
                    $fieldRules[] = "string|max:{$length}";
                } else {
                    $fieldRules[] = 'string';
                }
            } elseif (Str::contains($type, ['decimal', 'float', 'double', 'real'])) {
                $fieldRules[] = 'numeric';
            } elseif (Str::contains($type, ['bigint', 'int', 'integer', 'mediumint', 'smallint', 'tinyint'])) {
                // Excluir tinyint(1) que son booleanos
                if (!Str::contains($type, 'tinyint(1)')) {
                    $fieldRules[] = 'integer';
                }
            } elseif (Str::contains($type, 'date')) {
                // Soporta date, datetime, timestamp
                $fieldRules[] = 'date';
            } elseif (Str::contains($type, 'time')) {
                $fieldRules[] = 'date_format:H:i:s';
            }

            // Combinar reglas
            if (!empty($fieldRules)) {
                $rules[$column->Field] = $fieldRules;
            }
        }

        return $rules;
    }

    protected function encodeKey(array $primaryKey): string
    {
        $encoded = base64_encode(json_encode($primaryKey));
        return rtrim(strtr($encoded, '+/', '-_'), '=');
    }

    protected function decodeKey(string $encoded): array
    {
        $decoded = base64_decode(strtr($encoded, '-_', '+/'));
        return json_decode($decoded, true) ?: [];
    }
}
