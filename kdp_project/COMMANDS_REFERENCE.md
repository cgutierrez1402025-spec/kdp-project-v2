# 🛠️ Comandos Útiles - KDP Author Manager

## 🚀 Instalación y Setup

```bash
# Crear nuevo proyecto
composer create-project laravel/laravel kdp-author-manager

# Instalar Filament
composer require filament/filament
php artisan filament:install --panels

# Configurar base de datos
cp .env.example .env
nano .env  # Editar variables DB

# Crear BD en MySQL
mysql -u root -p -e "CREATE DATABASE kdp_author_manager CHARACTER SET utf8mb4"

# Ejecutar migraciones y seeders
php artisan migrate --seed

# Compilar assets
npm install
npm run build

# Crear usuario admin
php artisan make:filament-user
```

---

## 📝 Generación de Código

### Modelos
```bash
# Crear modelo con migración y factory
php artisan make:model Work -mf

# Con controlador y migrate
php artisan make:model Publication -mcr

# Solo modelo
php artisan make:model Illustration

# Listar todos los modelos
php artisan model:list

# Ver estructura de modelo
php artisan model:show Work
```

### Migraciones
```bash
# Crear migración
php artisan make:migration create_works_table
php artisan make:migration add_status_to_works

# Ejecutar migraciones
php artisan migrate

# Rollback última migración
php artisan migrate:rollback

# Rollback todas
php artisan migrate:reset

# Rollback + migrate nuevamente
php artisan migrate:refresh

# Refresh con seeders
php artisan migrate:refresh --seed

# Ver migraciones ejecutadas
php artisan migrate:status
```

### Seeders
```bash
# Crear seeder
php artisan make:seeder DemoDataSeeder

# Ejecutar seeders
php artisan db:seed

# Ejecutar seeder específico
php artisan db:seed --class=DemoDataSeeder

# Refresh y reseed
php artisan migrate:refresh --seed
```

### Filament Resources
```bash
# Crear resource
php artisan make:filament-resource Work

# Con página (CMS simple)
php artisan make:filament-resource Work --generate

# Solo resource sin páginas
php artisan make:filament-resource Work --simple

# Crear página de dashboard
php artisan make:filament-dashboard

# Crear widget
php artisan make:filament-widget StatsOverview
```

---

## 🔧 Comandos de Utilidad

### Base de Datos
```bash
# Crear base de datos en CLI
php artisan migrate

# Seed manual de data específica
php artisan tinker
>>> Work::create(['title_internal' => 'Test', ...])

# Truncar tabla (eliminar todos los registros)
php artisan tinker
>>> DB::table('works')->truncate()

# Ver queries SQL ejecutadas
php artisan tinker
>>> DB::enableQueryLog(); Work::all(); dd(DB::getQueryLog());
```

### Laravel Tinker (REPL interactivo)
```bash
php artisan tinker

# En tinker:
>>> $work = new Work();
>>> $work->title_internal = "Mi Obra";
>>> $work->author_name = "Yo";
>>> $work->original_language = "es";
>>> $work->save();

>>> $works = Work::all();
>>> $works->count()

>>> Work::where('status', 'publicada')->get()

>>> $work = Work::find(1);
>>> $work->languages()->count()

>>> User::find(1)->works()->first()->title_public

>>> DB::table('works')->count()
```

### Cache
```bash
# Limpiar todo el caché
php artisan cache:clear

# Limpiar caché de configuración
php artisan config:cache

# Limpiar caché de rutas
php artisan route:cache

# Limpiar caché de vistas
php artisan view:clear

# Limpiar todos los caches
php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear
```

### Storage y Ficheros
```bash
# Crear enlace simbólico a storage
php artisan storage:link

# Limpiar ficheros orphans
php artisan storage:cleanup

# Listar discos disponibles
php artisan tinker
>>> Storage::disks()

# Ver ficheros en public
ls -la storage/app/public/
```

---

## 🐳 Comandos Docker

### Instalación y Setup
```bash
# Construir imagen
docker-compose build

# Levantar servicios
docker-compose up -d

# Ver estado
docker-compose ps

# Bajar servicios
docker-compose down

# Logs del contenedor app
docker-compose logs app

# Logs en tiempo real
docker-compose logs -f app
```

### Ejecución dentro de Docker
```bash
# Ejecutar comando en app
docker exec kdp-app php artisan migrate

# Ejecutar composer
docker exec kdp-app composer install

# Ejecutar npm
docker exec kdp-app npm run build

# Acceder al bash
docker exec -it kdp-app bash

# Ejecutar tinker dentro de Docker
docker exec -it kdp-app php artisan tinker
```

### Debugging
```bash
# Ver IP del contenedor
docker inspect kdp-app | grep IPAddress

# Ver variables de entorno
docker exec kdp-app env

# Ver volúmenes
docker volume ls

# Inspeccionar base de datos
docker exec kdp-db mysql -u kdp_user -p kdp_author_manager -e "SHOW TABLES;"
```

---

## 🧪 Testing

### PHPUnit
```bash
# Ejecutar todos los tests
php artisan test

# Test un archivo específico
php artisan test tests/Unit/Models/WorkTest.php

# Test con verbose
php artisan test --verbose

# Test con coverage
php artisan test --coverage

# Ver resultados en HTML
php artisan test --coverage-html=coverage
```

### Crear Tests
```bash
# Test de unidad
php artisan make:test Models/WorkTest --unit

# Test funcional
php artisan make:test Controllers/WorkControllerTest

# Test de Feature
php artisan make:test Features/WorkFeatureTest
```

---

## 📊 Queries SQL Útiles

### En terminal MySQL
```bash
# Conectar a BD
mysql -u kdp_user -p kdp_author_manager

# Ver tablas
SHOW TABLES;

# Ver estructura de tabla
DESCRIBE works;
SHOW COLUMNS FROM works;

# Ver índices
SHOW INDEXES FROM works;

# Estadísticas de tamaño
SELECT table_name, ROUND(((data_length + index_length) / 1024 / 1024), 2) AS size_mb
FROM information_schema.TABLES
WHERE table_schema = 'kdp_author_manager'
ORDER BY (data_length + index_length) DESC;

# Contar registros
SELECT COUNT(*) FROM works;

# Ver registros con relaciones
SELECT w.title_public, wl.language_code, p.status
FROM works w
LEFT JOIN work_languages wl ON w.id = wl.work_id
LEFT JOIN publications p ON w.id = p.work_id;
```

---

## 🔍 Debugging y Troubleshooting

### Logs
```bash
# Ver logs en tiempo real
tail -f storage/logs/laravel.log

# Filtrar errores
grep ERROR storage/logs/laravel.log

# Ver últimas 50 líneas
tail -50 storage/logs/laravel.log

# Limpiar logs
> storage/logs/laravel.log
```

### Información del Sistema
```bash
# Ver versiones
php -v
composer -v
npm -v
node -v

# Ver extensiones PHP
php -m

# Ver información de Laravel
php artisan about

# Ver rutas
php artisan route:list

# Ver configuración
php artisan config:list
```

### Performance
```bash
# Ver queries ejecutadas
php artisan tinker
>>> DB::enableQueryLog();
>>> Work::with('languages')->get();
>>> dd(DB::getQueryLog());

# Ver tiempos de ejecución
DB::listen(function ($query) {
    echo $query->time . " ms: " . $query->sql;
});
```

---

## 📦 Mantenimiento

### Actualización de Dependencias
```bash
# Composer
composer update

# npm
npm update

# Check vulnerabilities
composer audit
npm audit
```

### Limpieza
```bash
# Limpiar logs viejos
php artisan logs:clear

# Limpiar temp files
php artisan queue:failed-table  # si usas queues

# Vaciar trash
rm -rf storage/app/temp/*
```

### Respaldos
```bash
# Backup BD
mysqldump -u kdp_user -p kdp_author_manager > backup.sql

# Restaurar BD
mysql -u kdp_user -p kdp_author_manager < backup.sql

# Backup archivos
tar -czf kdp-backup.tar.gz storage/app/public
```

---

## 🚀 Deployment

### Preparar para Producción
```bash
# Compilar assets optimizados
npm run build

# Caché configuración
php artisan config:cache

# Caché rutas
php artisan route:cache

# Caché vistas
php artisan view:cache

# Ejecutar migraciones
php artisan migrate --force

# Seed (si es necesario)
php artisan db:seed --force
```

### En Servidor de Producción
```bash
# Clonar repositorio
git clone <repo> kdp-author-manager
cd kdp-author-manager

# Instalar dependencias
composer install --no-dev --optimize-autoloader
npm ci

# Configurar .env
cp .env.example .env
nano .env  # Configurar variables

# Generar clave
php artisan key:generate

# Crear BD
# (crear manualmente en servidor)

# Ejecutar migraciones
php artisan migrate --force

# Ejecutar seeders (si aplica)
php artisan db:seed --force

# Compilar assets
npm run build

# Establecer permisos
chmod -R 775 storage bootstrap/cache

# Crear enlace simbólico
php artisan storage:link
```

---

## 💡 Tips de Productividad

### Alias Útiles (agregar a ~/.zshrc o ~/.bashrc)
```bash
alias artisan="php artisan"
alias sail="./vendor/bin/sail"
alias tinker="php artisan tinker"
alias test="php artisan test"
alias migrate="php artisan migrate"
alias seed="php artisan db:seed"
alias cache-clear="php artisan cache:clear && php artisan config:clear"
```

### VSCode Extensions Recomendadas
```
• PHP Intelephense
• Laravel Extension Pack
• Laravel Artisan
• Laravel Blade Spacer
• MySQL Client
• Docker
```

### Configuración de XDebug (Debugging)
```bash
# En .env
XDEBUG_MODE=develop,debug
XDEBUG_CONFIG="idekey=vscode"

# En VSCode settings.json
{
  "php.debug.executablePath": "/usr/bin/php",
  "debug.console.fontSize": 12
}
```

---

## 🆘 Comandos de Emergencia

```bash
# Reset completo
php artisan migrate:reset
php artisan migrate --seed

# Limpiar todo
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Reinstalar en Docker
docker-compose down -v
docker-compose up -d
docker exec kdp-app php artisan migrate --seed

# Eliminar caché y logs
rm -rf storage/framework/cache/*
rm -rf storage/logs/*
> storage/logs/laravel.log
```

---

## 📚 Recursos Útiles

- [Laravel Commands](https://laravel.com/docs/artisan)
- [Filament Docs](https://filamentphp.com)
- [MySQL Reference](https://dev.mysql.com/doc/refman/)
- [Docker Docs](https://docs.docker.com)
- [Git Commands](https://git-scm.com/docs)

---

**Última actualización**: 2025  
**Versión**: 1.0
