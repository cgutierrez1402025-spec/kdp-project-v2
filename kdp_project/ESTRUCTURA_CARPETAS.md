# KDP Author Manager - Estructura del Proyecto

Esta es la estructura recomendada después de instalar el proyecto.

```
kdp-author-manager/
│
├── app/
│   ├── Models/                          # 30+ Modelos Eloquent
│   │   ├── Work.php
│   │   ├── Series.php
│   │   ├── Edition.php
│   │   ├── WorkLanguage.php
│   │   ├── ManuscriptVersion.php
│   │   ├── Chapter.php
│   │   ├── Publication.php
│   │   ├── Illustration.php
│   │   ├── RoyaltyEntry.php
│   │   ├── BookPromotion.php
│   │   ├── DistributionPoint.php
│   │   ├── BookEvent.php
│   │   └── ... (20+ más)
│   │
│   ├── Filament/
│   │   └── Resources/                   # 15+ Resources Filament
│   │       ├── WorkResource.php         # ✅ Ejemplo incluido
│   │       ├── EditionResource.php
│   │       ├── PublicationResource.php
│   │       ├── RoyaltyEntryResource.php
│   │       ├── BookPromotionResource.php
│   │       └── ... (10+ más)
│   │
│   ├── Http/
│   │   ├── Controllers/                 # Controladores (si es necesario)
│   │   └── Middleware/
│   │
│   └── Observers/                       # Observers para eventos
│       ├── WorkObserver.php
│       └── StockMovementObserver.php
│
├── database/
│   ├── migrations/                      # Migraciones Laravel
│   │   ├── 2025_01_01_000000_create_all_tables.php ✅ (migration_ddl.php)
│   │   └── ... (migraciones adicionales)
│   │
│   ├── seeders/                         # Seeders
│   │   ├── DatabaseSeeder.php           # ✅ Ejemplo incluido
│   │   ├── DemoDataSeeder.php
│   │   ├── PlatformSeeder.php
│   │   └── ... (más seeders)
│   │
│   └── schema/
│       └── kdp_ddl.sql                  # ✅ Script SQL (incluido)
│
├── resources/
│   ├── css/
│   │   └── app.css
│   │
│   ├── js/
│   │   └── app.js
│   │
│   └── views/                           # Vistas Blade (opcional)
│       ├── layouts/
│       └── components/
│
├── routes/
│   ├── web.php
│   ├── api.php                          # API REST (futuro)
│   └── filament.php                     # Rutas Filament (auto)
│
├── storage/
│   └── app/
│       └── public/                      # Ficheros públicos
│           ├── manuscripts/             # HTML de manuscritos
│           ├── illustrations/           # Portadas e imágenes
│           └── assets/                  # Materiales promocionales
│
├── tests/
│   ├── Unit/                            # Tests unitarios
│   │   ├── Models/
│   │   └── Validations/
│   │
│   └── Feature/                         # Tests funcionales
│       ├── Resources/
│       └── API/
│
├── docker/                              # Configuración Docker (opcional)
│   └── nginx/
│       └── default.conf
│
├── docs/                                # Documentación adicional
│   ├── API.md
│   ├── DATABASE.md
│   └── DEPLOYMENT.md
│
├── .env.example                         # ✅ Variables de entorno
├── .gitignore
├── Dockerfile                           # ✅ Imagen Docker
├── docker-compose.yml                   # ✅ Orquestación Docker
├── install.sh                           # ✅ Script instalación
├── package.json
├── composer.json
├── artisan
└── ... (archivos laravel estándar)
```

## 📁 Carpetas Clave

### `/app/Models/`
Contiene los 30+ modelos Eloquent que definen la lógica de negocio.

**Ejemplo proporcionado:**
- `Work_model.php` → Copiar a `app/Models/Work.php`

### `/app/Filament/Resources/`
Contiene los 15+ Resources que generan el CRUD automático en Filament.

**Ejemplo proporcionado:**
- `WorkResource.php` → Copiar a `app/Filament/Resources/WorkResource.php`

### `/database/`
- **migrations/** → Scripts de creación de tablas
- **seeders/** → Datos de ejemplo
- **schema/** → `kdp_ddl.sql` (script SQL completo)

### `/storage/app/public/`
Almacena ficheros de usuarios:
- Manuscritos HTML
- Portadas e ilustraciones
- Activos promocionales

### `/routes/`
Define las rutas de la aplicación.
Filament maneja sus propias rutas automáticamente.

### `/tests/`
Tests unitarios y funcionales.

## 🚀 Instalación y Estructura

Después de ejecutar `./install.sh`:

1. **Proyecto creado** en `kdp-author-manager/`
2. **BD creada** con 60+ tablas
3. **Seeder ejecutado** con datos de ejemplo
4. **Admin panel** listo en `/admin`

## 📊 Relación entre Carpetas

```
Models (app/Models/) 
    ↓
Resources (app/Filament/Resources/) 
    ↓
Admin Panel (http://localhost:8000/admin)
    ↓
Base de Datos (/database)
```

## 🔄 Flujo de Desarrollo Típico

1. **Crear modelo** en `app/Models/Work.php`
2. **Crear migration** en `database/migrations/`
3. **Crear Resource** en `app/Filament/Resources/WorkResource.php`
4. **Definir relaciones** en modelo y resource
5. **Agregar validaciones** en resource
6. **Ejecutar migración** (`php artisan migrate`)
7. **Ver en admin panel** (`http://localhost:8000/admin`)

## 📝 Archivos Incluidos en Este Repositorio

- ✅ `00_LEEME_PRIMERO.txt` - Guía de inicio
- ✅ `README.md` - Documentación completa
- ✅ `SETUP_GUIDE.md` - Instalación paso a paso
- ✅ `IMPLEMENTATION_CHECKLIST.md` - Checklist de desarrollo
- ✅ `COMMANDS_REFERENCE.md` - Referencia de comandos
- ✅ `Work_model.php` - Ejemplo de modelo
- ✅ `WorkResource.php` - Ejemplo de Resource
- ✅ `DatabaseSeeder.php` - Datos de ejemplo
- ✅ `kdp_ddl.sql` - Script SQL (60+ tablas)
- ✅ `migration_ddl.php` - Migración para ejecutar DDL
- ✅ `Dockerfile` - Imagen Docker
- ✅ `docker-compose.yml` - Orquestación
- ✅ `install.sh` - Script instalación automática

## 🎯 Próximos Pasos

1. Ejecutar: `./install.sh`
2. Crear 30+ modelos copiando el patrón de `Work_model.php`
3. Crear 15+ Resources copiando el patrón de `WorkResource.php`
4. Implementar lógica de negocio (Observers, Validations)
5. Crear dashboard con widgets
6. Testing

Ver `IMPLEMENTATION_CHECKLIST.md` para detalles.
