@props(['tables'])

@php
// Mapeo de tablas a categorías con nombres legibles e iconos
$categorizedTables = [
    'Núcleo Editorial' => [
        'icon' => '📚',
        'color' => 'blue',
        'tables' => ['work', 'edition', 'chapter', 'illustration', 'illustration_anchor', 'illustration_version', 'aplus_module', 'aplus_project', 'award', 'award_submission']
    ],
    'Publicaciones KDP' => [
        'icon' => '📕',
        'color' => 'purple',
        'tables' => ['book_delivery', 'book_event', 'book_promotion', 'event_book']
    ],
    'Regalías y Pagos' => [
        'icon' => '💰',
        'color' => 'green',
        'tables' => ['royalty', 'payment', 'invoice', 'financial_report']
    ],
    'Promociones' => [
        'icon' => '🎯',
        'color' => 'yellow',
        'tables' => ['promotion', 'book_promotion', 'promotional_campaign']
    ],
    'Distribución Física' => [
        'icon' => '📦',
        'color' => 'orange',
        'tables' => ['distribution_point', 'distribution_visit', 'delivery_review', 'book_delivery']
    ],
    'IA/OCR' => [
        'icon' => '🤖',
        'color' => 'indigo',
        'tables' => ['ai_task', 'ai_tool', 'calibre_import', 'import_batch']
    ],
    'Administración y Permisos' => [
        'icon' => '⚙️',
        'color' => 'slate',
        'tables' => ['user', 'role', 'permission', 'activity_log', 'audit_log']
    ]
];

// Mapeo de nombres de tablas a títulos legibles
$tableNames = [
    'work' => 'Obras',
    'edition' => 'Ediciones',
    'chapter' => 'Capítulos',
    'illustration' => 'Ilustraciones',
    'illustration_anchor' => 'Anclajes de Ilustraciones',
    'illustration_version' => 'Versiones de Ilustraciones',
    'aplus_module' => 'Módulos A+',
    'aplus_project' => 'Proyectos A+',
    'award' => 'Premios',
    'award_submission' => 'Envíos de Premios',
    'book_delivery' => 'Entregas de Libros',
    'book_event' => 'Eventos de Libros',
    'book_promotion' => 'Promociones de Libros',
    'event_book' => 'Libros de Eventos',
    'royalty' => 'Regalías',
    'payment' => 'Pagos',
    'invoice' => 'Facturas',
    'financial_report' => 'Reportes Financieros',
    'promotion' => 'Promociones',
    'promotional_campaign' => 'Campañas Promocionales',
    'distribution_point' => 'Puntos de Distribución',
    'distribution_visit' => 'Visitas de Distribución',
    'delivery_review' => 'Revisiones de Entregas',
    'ai_task' => 'Tareas de IA',
    'ai_tool' => 'Herramientas de IA',
    'calibre_import' => 'Importaciones de Calibre',
    'import_batch' => 'Lotes de Importación',
    'user' => 'Usuarios',
    'role' => 'Roles',
    'permission' => 'Permisos',
    'activity_log' => 'Registro de Actividad',
    'audit_log' => 'Registro de Auditoría',
];

// Organizar tablas disponibles por categoría
$sections = [];
foreach ($categorizedTables as $category => $config) {
    $categorized = [];
    foreach ($config['tables'] as $tableName) {
        if (in_array($tableName, $tables->toArray())) {
            $categorized[] = $tableName;
        }
    }
    if (!empty($categorized)) {
        $sections[$category] = [
            'icon' => $config['icon'],
            'color' => $config['color'],
            'tables' => $categorized
        ];
    }
}

// Tablas que no entraron en categorías
$uncategorized = array_diff($tables->toArray(), array_merge(...array_column($sections, 'tables')));
if (!empty($uncategorized)) {
    $sections['Otros'] = [
        'icon' => '📋',
        'color' => 'slate',
        'tables' => $uncategorized
    ];
}

// Colores Tailwind por categoría
$colorMap = [
    'blue' => 'from-blue-50 dark:from-blue-900/20 border-blue-200 dark:border-blue-800',
    'purple' => 'from-purple-50 dark:from-purple-900/20 border-purple-200 dark:border-purple-800',
    'green' => 'from-green-50 dark:from-green-900/20 border-green-200 dark:border-green-800',
    'yellow' => 'from-yellow-50 dark:from-yellow-900/20 border-yellow-200 dark:border-yellow-800',
    'orange' => 'from-orange-50 dark:from-orange-900/20 border-orange-200 dark:border-orange-800',
    'indigo' => 'from-indigo-50 dark:from-indigo-900/20 border-indigo-200 dark:border-indigo-800',
    'slate' => 'from-slate-50 dark:from-slate-900/20 border-slate-200 dark:border-slate-800',
];

$iconColorMap = [
    'blue' => 'bg-blue-100 dark:bg-blue-900/40 text-blue-600 dark:text-blue-400',
    'purple' => 'bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-400',
    'green' => 'bg-green-100 dark:bg-green-900/40 text-green-600 dark:text-green-400',
    'yellow' => 'bg-yellow-100 dark:bg-yellow-900/40 text-yellow-600 dark:text-yellow-400',
    'orange' => 'bg-orange-100 dark:bg-orange-900/40 text-orange-600 dark:text-orange-400',
    'indigo' => 'bg-indigo-100 dark:bg-indigo-900/40 text-indigo-600 dark:text-indigo-400',
    'slate' => 'bg-slate-100 dark:bg-slate-900/40 text-slate-600 dark:text-slate-400',
];

$buttonColorMap = [
    'blue' => 'bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600',
    'purple' => 'bg-purple-600 hover:bg-purple-700 dark:bg-purple-700 dark:hover:bg-purple-600',
    'green' => 'bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-600',
    'yellow' => 'bg-yellow-600 hover:bg-yellow-700 dark:bg-yellow-700 dark:hover:bg-yellow-600',
    'orange' => 'bg-orange-600 hover:bg-orange-700 dark:bg-orange-700 dark:hover:bg-orange-600',
    'indigo' => 'bg-indigo-600 hover:bg-indigo-700 dark:bg-indigo-700 dark:hover:bg-indigo-600',
    'slate' => 'bg-slate-600 hover:bg-slate-700 dark:bg-slate-700 dark:hover:bg-slate-600',
];
@endphp

<x-admin-layout>
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-slate-900 dark:text-white mb-2">Panel Administrativo</h1>
        <p class="text-lg text-slate-600 dark:text-slate-400">Accede a los módulos del sistema para gestionar todos los aspectos de KDP Author Manager</p>
    </div>

    <!-- Sections -->
    <div class="space-y-8">
        @foreach($sections as $categoryName => $categoryData)
            <section class="mb-8">
                <!-- Category Header -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="text-3xl">{{ $categoryData['icon'] }}</div>
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">{{ $categoryName }}</h2>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Gestiona todos los elementos relacionados con {{ strtolower($categoryName) }}</p>
                    </div>
                </div>

                <!-- Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($categoryData['tables'] as $table)
                        @php
                            $tableName = $tableNames[$table] ?? str_replace('_', ' ', ucwords($table));
                            $colorClass = $colorMap[$categoryData['color']] ?? $colorMap['slate'];
                            $iconColorClass = $iconColorMap[$categoryData['color']] ?? $iconColorMap['slate'];
                            $buttonColorClass = $buttonColorMap[$categoryData['color']] ?? $buttonColorMap['slate'];
                        @endphp
                        <div class="group bg-gradient-to-br {{ $colorClass }} rounded-lg border p-6 transition-all duration-300 hover:shadow-lg hover:scale-105">
                            <!-- Card Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">{{ $tableName }}</h3>
                                    <p class="text-sm text-slate-600 dark:text-slate-400">
                                        Gestiona todos los registros de {{ strtolower($tableName) }} del sistema
                                    </p>
                                </div>
                            </div>

                            <!-- Stats or Info -->
                            <div class="mb-5 py-3 border-t border-b border-slate-200 dark:border-slate-700">
                                <div class="flex items-center gap-2 text-sm text-slate-600 dark:text-slate-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h12a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM4 16a1 1 0 00-1 1v2a1 1 0 001 1h12a1 1 0 001-1v-2a1 1 0 00-1-1H4z"/>
                                    </svg>
                                    Tabla: <code class="ml-1 px-2 py-1 bg-slate-200 dark:bg-slate-700 rounded text-xs font-mono">{{ $table }}</code>
                                </div>
                            </div>

                            <!-- Action Button -->
                            <a href="{{ route('admin.table.index', ['table' => $table]) }}" class="inline-flex items-center gap-2 px-4 py-2 {{ $buttonColorClass }} text-white font-medium rounded-lg transition-all duration-200 hover:shadow-md group-hover:shadow-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                                Abrir
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>

    <!-- Footer Info -->
    <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-700">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ count($tables) }}</div>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Módulos disponibles</p>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ count($sections) }}</div>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Categorías</p>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600 dark:text-green-400">✓</div>
                <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">Sistema operativo</p>
            </div>
        </div>
    </div>
</x-admin-layout>
