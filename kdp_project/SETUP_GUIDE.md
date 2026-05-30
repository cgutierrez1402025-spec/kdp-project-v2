# 🚀 KDP Author Manager - Guía Completa de Instalación

Una aplicación Laravel completa para gestionar la autoedición de libros, integrando Filament, MySQL y Bootstrap.

## 📋 Requisitos Previos

- PHP ≥ 8.2
- Composer
- MySQL 8.0 o MariaDB
- Node.js & npm
- Git

Alternativamente, usa Docker (ver sección Docker).

---

## 🐳 Opción 1: Instalación con Docker (Recomendado)

```bash
# Clonar el repositorio
git clone <tu-repositorio> kdp-author-manager
cd kdp-author-manager

# Construir y lanzar contenedores
docker-compose up -d

# Dentro del contenedor app, instalar dependencias
docker exec kdp-app composer install

# Generar clave de aplicación
docker exec kdp-app php artisan key:generate

# Ejecutar migraciones y seeders
docker exec kdp-app php artisan migrate --seed

# Acceder a la aplicación
# http://localhost:8000 (Laravel)
# http://localhost:8080 (PhpMyAdmin)
```

---

## 💻 Opción 2: Instalación Local

### Paso 1: Crear proyecto Laravel

```bash
composer create-project laravel/laravel kdp-author-manager
cd kdp-author-manager
```

### Paso 2: Configurar base de datos

Editar `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kdp_author_manager
DB_USERNAME=root
DB_PASSWORD=
```

Crear base de datos en MySQL:

```sql
CREATE DATABASE kdp_author_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Paso 3: Instalar Filament

```bash
composer require filament/filament
php artisan filament:install --panels
php artisan migrate
php artisan make:filament-user
```

### Paso 4: Ejecutar las migraciones y seeders

```bash
php artisan migrate
php artisan db:seed
```

### Paso 5: Compilar assets

```bash
npm install
npm run dev
```

### Paso 6: Iniciar servidor

```bash
php artisan serve
```

Acceder a `http://localhost:8000`

---

## 🗂️ Estructura del Proyecto

```
kdp-author-manager/
├── app/
│   ├── Models/          (Modelos Eloquent)
│   ├── Filament/
│   │   └── Resources/   (CRUD Resources de Filament)
│   └── Http/
│       └── Controllers/
├── database/
│   ├── migrations/      (Migraciones)
│   ├── seeders/         (Seeders)
│   └── schema/          (DDL SQL)
├── resources/
│   └── views/
├── routes/
├── storage/             (Almacenamiento de ficheros)
└── .env
```

---

## 📦 Entidades Principales (Fase 1)

1. **Works** - Obras literarias
2. **WorkLanguages** - Idiomas de las obras
3. **Editions** - Ediciones de publicaciones
4. **ManuscriptVersions** - Versiones del manuscrito (HTML)
5. **Publications** - Publicaciones en plataformas (KDP, etc.)
6. **RoyaltyEntries** - Registros de regalías
7. **BookPromotions** - Promociones y descuentos
8. **DistributionPoints** - Librerías y puntos de venta

---

## 🎯 CRUD Resources de Filament

Una vez ejecutes los comandos de generación, cada recurso incluirá:

- ✅ Listado paginado con búsqueda
- ✅ Creación y edición de registros
- ✅ Eliminación segura
- ✅ Filtros avanzados
- ✅ Validaciones automáticas
- ✅ Relaciones Many-to-Many y One-to-Many

---

## 🔐 Roles y Permisos

Se incluyen tablas de roles y permisos (preconfiguradas para Filament).

Roles predeterminados:
- **admin** - Acceso total
- **author** - Gestión de sus propias obras
- **editor** - Edición de manuscritos

---

## 💾 Almacenamiento de Ficheros

Los archivos HTML, imágenes e ilustraciones se guardan en:

```
storage/app/public/
├── manuscripts/    (HTML de manuscritos)
├── illustrations/  (Portadas e imágenes)
└── assets/         (Materiales promocionales)
```

Enlace simbólico:

```bash
php artisan storage:link
```

---

## 🌱 Seeders y Datos de Ejemplo

Ejecutar seeders para poblar con datos de prueba:

```bash
php artisan db:seed --class=DemoDataSeeder
```

Se crearán automáticamente:
- 5 usuarios de ejemplo
- 10 obras de ejemplo
- 5 series de ejemplo
- Publicaciones en KDP y Amazon
- Registros de regalías
- Promociones activas

---

## 🚀 Primeros Pasos en Filament

1. Accede a `http://localhost:8000/admin`
2. Inicia sesión con tus credenciales
3. Navega por los recursos disponibles:
   - **Works** - Gestionar obras
   - **Editions** - Gestionar ediciones
   - **Publications** - Publicaciones en plataformas
   - **Royalty Entries** - Regalías
   - Y más...

---

## 📊 Funcionalidades Implementadas

### CRUD Completo
- Crear, leer, actualizar, eliminar obras, ediciones, publicaciones
- Relaciones automáticas entre entidades

### Búsqueda y Filtros
- Búsqueda por título, autor, género
- Filtros por estado, idioma, plataforma
- Filtros de fechas

### Paginación
- 10, 25, 50 registros por página
- Navegación fluida

### Validaciones
- Campos requeridos
- Longitudes mínimas/máximas
- Emails válidos
- Valores únicos (ISBN, ASIN)

### Almacenamiento
- Subida de ficheros HTML
- Gestión de imágenes (portadas, ilustraciones)
- Versioning automático

---

## 🔄 Flujo de Trabajo Típico

1. **Crear obra** en Works
2. **Agregar idiomas** en WorkLanguages
3. **Subir manuscrito** en ManuscriptVersions (HTML)
4. **Agregar ilustraciones**
5. **Crear publicación** en Publications (seleccionar plataforma)
6. **Configurar KDP Select** (si aplicable)
7. **Registrar promociones** en BookPromotions
8. **Importar regalías** mensualmente en RoyaltyEntries
9. **Consultar reportes** y dashboards

---

## 📈 Dashboards y Reportes

Acceso a dashboards con:
- Total de ingresos por mes
- Obras más vendidas
- Rendimiento por plataforma
- Alertas de próximos vencimientos

---

## 🛠️ Personalización

### Agregar campos a un formulario

Editar `app/Filament/Resources/WorkResource.php`:

```php
public static function form(Form $form): Form
{
    return $form->schema([
        TextInput::make('title_public')->required()->minLength(3)->maxLength(255),
        TextInput::make('author_name')->required(),
        Select::make('status')->options([
            'idea' => 'Idea',
            'redaccion' => 'Redacción',
            'publicada' => 'Publicada',
        ])->required(),
        // ... más campos
    ]);
}
```

### Agregar validaciones personalizadas

```php
TextInput::make('isbn')
    ->regex('/^[0-9\-]{10,20}$/')
    ->message('ISBN inválido'),
```

---

## 🐛 Troubleshooting

**Error: "Class not found"**
```bash
composer dump-autoload
```

**Error: "SQLSTATE[HY000]: General error: 1030"**
→ Aumentar `max_allowed_packet` en MySQL:
```sql
SET GLOBAL max_allowed_packet = 268435456;
```

**Error: "Permission denied" en storage**
```bash
chmod -R 775 storage bootstrap/cache
```

---

## 📚 Recursos Útiles

- [Laravel Docs](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com)
- [Eloquent Relationships](https://laravel.com/docs/eloquent-relationships)
- [Laravel Migrations](https://laravel.com/docs/migrations)

---

## 📝 Próximos Pasos

1. ✅ Instalar y configurar
2. ✅ Explorar Filament admin panel
3. ✅ Crear tus primeras obras
4. ✅ Subir manuscritos y portadas
5. ✅ Crear publicaciones
6. ✅ Personalizar formularios y validaciones
7. ⏳ Agregar lógica de negocio personalizada
8. ⏳ Crear vistas públicas (website)
9. ⏳ Integrar API externa (KDP, Amazon, etc.)

---

**¡Listo! Tu aplicación KDP Author Manager está lista para gestionar tu negocio de autoedición. 📚✨**
