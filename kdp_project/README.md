# 📚 KDP Author Manager - Aplicación Laravel Completa

## Descripción

**KDP Author Manager** es una aplicación web profesional construida con Laravel + Filament para gestionar integralmente el negocio de autoedición y publicación de libros. Integra gestión de obras, manuscritos, publicaciones en plataformas (KDP, Amazon, etc.), regalías, promociones, distribución física y mucho más.

### Características Principales

✅ **Gestión de Obras**: Crear, editar y organizar obras literarias  
✅ **Ediciones Multiidioma**: Gestión de traducciones y variantes regionales  
✅ **Manuscritos Versionados**: Historial completo de versiones con HTML  
✅ **Plataformas de Publicación**: KDP, Amazon, Smashwords, etc.  
✅ **KDP Select y Promociones**: Control de períodos de exclusividad y promociones  
✅ **Regalías Automáticas**: Importación y análisis de regalías mensuales  
✅ **Distribución Física**: Gestión de librerías, entregas y stock  
✅ **Eventos y Presentaciones**: Seguimiento de eventos con registro de ventas  
✅ **Premios Literarios**: Base de datos de premios y convocatorias  
✅ **Inteligencia Artificial**: Integración con herramientas IA para contenido  
✅ **OCR y Calibre**: Importación desde librerías digitales  
✅ **Dashboard Ejecutivo**: Reportes y visualización de datos  
✅ **Roles y Permisos**: Control de acceso granular  
✅ **API Escalable**: Base para futuras integraciones

---

## 🚀 Instalación Rápida (5 minutos)

### Opción 1: Docker (Recomendado)

```bash
# 1. Clonar repositorio
git clone <tu-repo> kdp-author-manager
cd kdp-author-manager

# 2. Copiar archivo de entorno
cp .env.example .env

# 3. Construir y lanzar contenedores
docker-compose up -d

# 4. Instalar dependencias
docker exec kdp-app composer install
docker exec kdp-app npm install

# 5. Generar clave
docker exec kdp-app php artisan key:generate

# 6. Ejecutar migraciones y seeders
docker exec kdp-app php artisan migrate --seed

# 7. Compilar assets
docker exec kdp-app npm run build

# ¡Listo! Accede a:
# - App: http://localhost:8000
# - Admin: http://localhost:8000/admin
# - PhpMyAdmin: http://localhost:8080
```

### Opción 2: Instalación Local

```bash
# 1. Requisitos
# - PHP 8.2+
# - MySQL 8.0
# - Composer
# - Node.js & npm

# 2. Crear proyecto
composer create-project laravel/laravel kdp-author-manager
cd kdp-author-manager

# 3. Instalar paquetes
composer require filament/filament
npm install

# 4. Configurar .env
# Editar DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 5. Generar clave y crear BD
php artisan key:generate
mysql -u root -p -e "CREATE DATABASE kdp_author_manager CHARACTER SET utf8mb4"

# 6. Ejecutar migraciones
php artisan migrate --seed

# 7. Compilar assets
npm run build

# 8. Iniciar servidor
php artisan serve

# Accede a http://localhost:8000/admin
```

---

## 🔑 Credenciales por Defecto

```
Email:    admin@kdpmanager.local
Password: password
```

**⚠️ Cambiar contraseña en producción**

---

## 📁 Estructura del Proyecto

```
kdp-author-manager/
├── app/
│   ├── Models/                    # Modelos Eloquent (40+ modelos)
│   ├── Filament/
│   │   ├── Resources/             # CRUD Resources (Works, Publications, etc.)
│   │   └── Pages/                 # Páginas personalizadas
│   ├── Http/
│   │   └── Controllers/
│   └── Providers/
├── database/
│   ├── migrations/                # Migraciones SQL
│   ├── seeders/                   # Seeders con datos de ejemplo
│   └── schema/
│       └── kdp_ddl.sql           # Script DDL completo
├── resources/
│   ├── views/
│   └── css/
├── routes/
├── storage/
│   └── app/
│       └── public/                # Ficheros (manuscritos, ilustraciones)
├── docker-compose.yml
└── .env
```

---

## 📊 Entidades Principales (60+ tablas)

### Módulo Core
- **works** - Obras literarias
- **series** - Series de libros
- **work_languages** - Idiomas de cada obra
- **editions** - Ediciones de publicaciones

### Manuscritos
- **manuscript_versions** - Versiones de manuscritos (HTML)
- **chapters** - Capítulos del manuscrito
- **sources** - Fuentes documentales

### Ilustraciones
- **illustrations** - Portadas e ilustraciones
- **illustration_versions** - Versiones de ilustraciones
- **illustration_anchors** - Anclajes en manuscritos

### Publicaciones
- **publications** - Publicaciones en plataformas
- **platforms** - Plataformas (KDP, Smashwords, etc.)
- **marketplaces** - Mercados por plataforma
- **kdp_metadata** - Metadatos KDP específicos

### KDP Select & Promociones
- **kdp_select_periods** - Períodos de KDP Select
- **book_promotions** - Promociones y descuentos
- **promotion_daily_results** - Resultados diarios
- **promotion_costs** - Costos de promoción

### Regalías
- **royalty_entries** - Registros de regalías mensuales
- **royalty_payments** - Pagos recibidos
- **payment_thresholds** - Límites de pago por plataforma

### Premios
- **awards** - Base de premios literarios
- **award_submissions** - Presentaciones a premios

### Eventos
- **book_events** - Eventos y presentaciones
- **event_books** - Libros en cada evento

### Distribución Física
- **physical_print_runs** - Tiradas de impresión
- **distribution_points** - Librerías y tiendas
- **stock_locations** - Ubicaciones de stock
- **stock_movements** - Movimientos de inventario
- **book_deliveries** - Entregas a librerías
- **delivery_reviews** - Revisiones de entregas

### IA y Contenido
- **ai_tools** - Herramientas IA (ChatGPT, Midjourney, etc.)
- **ai_tasks** - Tareas IA por obra
- **prompts** - Base de prompts reutilizables

### Importación y OCR
- **import_batches** - Lotes de importación
- **import_mappings** - Mapeo de campos
- **import_rows** - Filas importadas
- **ocr_jobs** - Trabajos OCR
- **calibre_imports** - Importación desde Calibre
- **translation_jobs** - Trabajos de traducción

### Materiales Promocionales
- **promotional_assets** - Portadas, banners, etc.
- **aplus_projects** - Proyectos A+ Amazon
- **aplus_modules** - Módulos A+

### Operacionales
- **tasks** - Tareas y asignaciones
- **comments** - Comentarios en obras/manuscritos
- **checklists** - Listas de verificación
- **tags** - Etiquetas personalizadas
- **activity_logs** - Registro de actividad

---

## 🎯 CRUD Resources en Filament (Listado Completo)

Cada uno incluye: creación, edición, eliminación, búsqueda, filtros, paginación, validaciones.

### Administración Principal
- **WorkResource** - Gestión de obras
- **SeriesResource** - Gestión de series
- **EditionResource** - Gestión de ediciones
- **WorkLanguageResource** - Idiomas de obras

### Manuscritos
- **ManuscriptVersionResource** - Versiones HTML
- **ChapterResource** - Capítulos individuales
- **SourceResource** - Fuentes documentales

### Ilustraciones
- **IllustrationResource** - Portadas e imágenes
- **IllustrationVersionResource** - Versiones

### Publicaciones
- **PublicationResource** - Publicaciones en plataformas
- **PlatformResource** - Gestión de plataformas
- **MarketplaceResource** - Mercados

### KDP Select
- **KdpSelectPeriodResource** - Períodos Select
- **BookPromotionResource** - Promociones
- **PromotionDailyResultResource** - Resultados

### Regalías
- **RoyaltyEntryResource** - Registros mensuales
- **RoyaltyPaymentResource** - Pagos recibidos

### Premios
- **AwardResource** - Base de premios
- **AwardSubmissionResource** - Presentaciones

### Eventos
- **BookEventResource** - Eventos
- **EventBookResource** - Libros en evento

### Distribución
- **PhysicalPrintRunResource** - Tiradas impresas
- **DistributionPointResource** - Librerías
- **StockMovementResource** - Inventario
- **BookDeliveryResource** - Entregas

### Otros
- **TaskResource** - Tareas
- **AIToolResource** - Herramientas IA
- **PromptResource** - Base de prompts

---

## 🔍 Funcionalidades Avanzadas

### 1. Búsqueda Global
Busca en: títulos, autores, géneros, estados, idiomas, ASINs, ISBNs

```
Ejemplo: "novela sci-fi español publicada"
```

### 2. Filtros Multidimensionales
- Por estado (idea, redacción, publicada)
- Por género y subgénero
- Por idioma original
- Por fecha de creación/publicación
- Por plataforma
- Por rango de precios
- Por regalías (mínimo/máximo)

### 3. Paginación Flexible
- 10, 25, 50, 100 registros por página
- Ordena por cualquier columna
- Exportación a CSV/Excel (Filament Pro)

### 4. Validaciones Inteligentes
- ISBN/ASIN únicos por marketplace
- Fechas lógicas (inicio < fin)
- Precios positivos
- Campos requeridos según estado
- Validación de email y URL

### 5. Relaciones Automáticas
- Crear obras → Añadir idiomas → Ediciones → Manuscritos → Publicar
- Vista de árboles de versiones
- Historial de cambios

### 6. Almacenamiento de Ficheros
- Manuscritos HTML: `storage/app/public/manuscripts/`
- Ilustraciones: `storage/app/public/illustrations/`
- Activos promocionales: `storage/app/public/assets/`
- Versioning automático

### 7. Dashboard Ejecutivo
- Ingresos totales por mes
- Top 5 obras más vendidas
- Obras pendientes de publicación
- Próximos vencimientos de KDP Select
- Alertas de regalías
- Tasa de conversión por plataforma

### 8. Reportes
- Regalías por plataforma/mes
- Análisis de promociones (ROI)
- Inventario físico
- Eventos y asistencia
- Análisis de premios

---

## 💾 Importación de Datos

### Desde KDP/Amazon
```bash
php artisan import:royalties <file.csv> --platform=kdp
php artisan import:publications <file.csv> --platform=kdp
```

### Desde Calibre
```bash
php artisan import:calibre <library-path>
```

### OCR de Documentos
```bash
php artisan ocr:process <file.pdf> --language=es
```

---

## 🔐 Control de Acceso

### Roles Predefinidos
- **Admin** - Acceso total
- **Author** - Solo sus obras
- **Editor** - Edición de contenido
- **Accountant** - Solo regalías y pagos

### Permisos Específicos
```php
// En formularios Filament
->hidden(fn() => ! auth()->user()->can('edit_prices'))
```

---

## 📈 Escalabilidad

### Base de Datos
- 60+ tablas con relaciones optimizadas
- Índices en búsquedas frecuentes
- Soporta millones de registros

### Cachés
```php
// Laravel cache integrado
Cache::remember('work.sales', 3600, fn() => Work::sumSales());
```

### API REST (Próximamente)
```
GET /api/v1/works
POST /api/v1/publications
GET /api/v1/royalties/{publication}
```

---

## 🛠️ Personalización

### Agregar Campo a Formulario

Editar `app/Filament/Resources/WorkResource.php`:

```php
public static function form(Form $form): Form {
    return $form->schema([
        // ... campos existentes ...
        
        // Nuevo campo personalizado
        Forms\Components\DatePicker::make('cover_ready_date')
            ->label('Portada Lista')
            ->helperText('Fecha en que la portada estará lista'),
    ]);
}
```

### Agregar Validación

```php
TextInput::make('isbn')
    ->rules([
        'regex:/^(?:ISBN(?:-1[03])?:?\ )?(?=[0-9X]{10}$|(?=(?:[0-9]+[- ]){3})[- 0-9X]{13}$|97[89][0-9]{10}$|(?=(?:[0-9]+[- ]){4})[- 0-9]{17}$)(?:97[89][- ]?)?[0-9]{1,5}[- ]?[0-9]+[- ]?[0-9]+[- ]?[0-9X]$/',
    ])
    ->message('ISBN inválido'),
```

### Agregar Acción Personalizada

```php
Tables\Actions\Action::make('generatePromoCode')
    ->label('Generar Código Promocional')
    ->action(fn(Work $record) => $record->generatePromoCode())
    ->icon('heroicon-o-sparkles'),
```

---

## 📚 Comandos Artisan Útiles

```bash
# Migraciones
php artisan migrate              # Ejecutar todas
php artisan migrate:rollback     # Deshacer últimas
php artisan migrate:refresh      # Reiniciar BD

# Seeders
php artisan db:seed              # Ejecutar seeders
php artisan db:seed --class=DemoDataSeeder

# Caché
php artisan cache:clear          # Limpiar caché
php artisan config:cache         # Caché configuración

# Storage
php artisan storage:link         # Enlace simbólico

# Filament
php artisan make:filament-resource WorkLanguage
php artisan make:filament-user    # Crear usuario admin

# Maintenance
php artisan tinker               # REPL interactivo
php artisan model:show Work      # Información del modelo
```

---

## 🐛 Troubleshooting

### Error: "Class not found"
```bash
composer dump-autoload
```

### Error: "SQLSTATE[HY000]"
```sql
SET GLOBAL max_allowed_packet = 268435456;
```

### Permission denied en storage
```bash
chmod -R 775 storage bootstrap/cache
```

### Base de datos no existe
```bash
mysql -u root -p -e "CREATE DATABASE kdp_author_manager"
```

---

## 📖 Documentación

- [Laravel Docs](https://laravel.com/docs)
- [Filament Docs](https://filamentphp.com/docs)
- [Eloquent Guide](https://laravel.com/docs/eloquent)
- [MySQL Documentation](https://dev.mysql.com/doc/)

---

## 🤝 Contribuciones

Las contribuciones son bienvenidas. Por favor:

1. Fork el repositorio
2. Crea una rama para tu feature
3. Commit con mensajes claros
4. Push y crea Pull Request

---

## 📝 Licencia

Este proyecto está bajo licencia MIT.

---

## 👨‍💻 Autor

Creado para autores que quieren gestionar su negocio de autoedición de forma profesional.

**Versión**: 1.0.0  
**Última actualización**: 2025

---

**¿Necesitas ayuda?** Consulta la guía de instalación completa o contacta al equipo de soporte.
