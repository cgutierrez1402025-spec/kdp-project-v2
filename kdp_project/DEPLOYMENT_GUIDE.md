# 🚀 GUÍA COMPLETA DE DESPLIEGUE - KDP Author Manager

Sigue esta guía para desplegar la aplicación localmente y verla en ejecución.

## 📋 Requisitos Previos

✅ Docker instalado (https://www.docker.com/products/docker-desktop)  
✅ Docker Compose (incluido en Docker Desktop)  
✅ Git instalado  
✅ Al menos 3 GB de RAM disponible  
✅ 500 MB de espacio en disco  

**Verificar instalación:**
```bash
docker --version
docker-compose --version
```

---

## 🚀 DESPLIEGUE RÁPIDO (5 MINUTOS)

### Opción 1: Script Automático (RECOMENDADO)

```bash
# 1. Clonar repositorio
git clone https://github.com/cgutierrez1402025-spec/kdp_project.git
cd kdp_project

# 2. Hacer script ejecutable
chmod +x deploy.sh

# 3. Ejecutar despliegue
./deploy.sh

# ¡Listo! Accede a http://localhost:8000/admin
```

**Tiempo:** ~3-5 minutos (depende de tu conexión internet)

### Opción 2: Despliegue Manual Paso a Paso

```bash
# 1. Clonar repositorio
git clone https://github.com/cgutierrez1402025-spec/kdp_project.git
cd kdp_project

# 2. Crear archivo .env
cp .env.example .env

# 3. Levantar contenedores
docker-compose up -d

# 4. Esperar a que MySQL esté listo (15 segundos)
sleep 15

# 5. Instalar dependencias
docker exec kdp-app composer install

# 6. Generar clave
docker exec kdp-app php artisan key:generate --force

# 7. Ejecutar migraciones
docker exec kdp-app php artisan migrate:fresh --seed --force

# 8. Crear enlace simbólico
docker exec kdp-app php artisan storage:link

# Acceder a http://localhost:8000/admin
```

---

## 📊 ACCESO A LA APLICACIÓN

Después de ejecutar el despliegue, accede a:

### URLs

| Servicio | URL | Descripción |
|----------|-----|-------------|
| **Aplicación** | http://localhost:8000 | Homepage |
| **Admin Panel** | http://localhost:8000/admin | Panel de administración |
| **PhpMyAdmin** | http://localhost:8080 | Gestor de base de datos |

### Credenciales

**Admin Panel:**
```
Email:    admin@kdpmanager.local
Password: password
```

**PhpMyAdmin:**
```
Usuario:  root
Password: root_password
```

**Base de Datos:**
```
Host:     localhost:3306 (o db dentro de Docker)
Database: kdp_author_manager
Usuario:  kdp_user
Password: kdp_password
```

---

## 🐳 COMANDOS DOCKER ÚTILES

### Ver estado de contenedores

```bash
# Ver todos los contenedores en ejecución
docker-compose ps

# Ver logs en tiempo real
docker-compose logs -f app

# Ver logs de específico servicio
docker-compose logs -f db
docker-compose logs -f phpmyadmin
```

### Acceder a contenedores

```bash
# Acceder a bash en el contenedor app
docker exec -it kdp-app bash

# Ejecutar php artisan commands
docker exec kdp-app php artisan tinker
docker exec kdp-app php artisan migrate:status

# Acceder a MySQL directamente
docker exec -it kdp-db mysql -u root -p kdp_author_manager
```

### Controlador de servicios

```bash
# Detener servicios
docker-compose down

# Reiniciar servicios
docker-compose restart

# Reiniciar solo app
docker-compose restart app

# Levantar servicios nuevamente
docker-compose up -d

# Eliminar contenedores y volúmenes
docker-compose down -v

# Ver estadísticas de CPU/memoria
docker stats
```

---

## 🔍 VERIFICAR QUE TODO FUNCIONA

### 1. Verificar Contenedores

```bash
docker-compose ps
# Deberías ver 3 contenedores en ejecución:
# - kdp-app
# - kdp-db
# - kdp-pma
```

### 2. Verificar Base de Datos

```bash
# Acceder a MySQL
docker exec -it kdp-db mysql -u root -p kdp_author_manager

# Dentro de MySQL:
SHOW TABLES;  # Ver todas las tablas (deberían ser 60+)
SELECT COUNT(*) FROM works;  # Ver obras de ejemplo
```

### 3. Verificar Aplicación

```bash
# Acceder a app
docker exec -it kdp-app bash

# Dentro del contenedor:
php artisan tinker
>>> User::count()    # Debería mostrar 3 (admin + 2 examples)
>>> Work::count()    # Debería mostrar 2 (obras de ejemplo)
>>> exit
```

### 4. Probar URLs

Abre en tu navegador:
- http://localhost:8000 → Deberías ver "Laravel"
- http://localhost:8000/admin → Te pide login
- http://localhost:8080 → PhpMyAdmin

---

## 🎯 DESPUÉS DE DESPLEGAR

### 1. Explorar la Aplicación (10 minutos)

✅ Accede a http://localhost:8000/admin  
✅ Inicia sesión con admin@kdpmanager.local / password  
✅ Explora el dashboard Filament  
✅ Ver datos de ejemplo (obras, usuarios, etc.)  

### 2. Entender la Estructura (30 minutos)

✅ Lee `README.md` - Documentación general  
✅ Lee `ESTRUCTURA_CARPETAS.md` - Estructura del proyecto  
✅ Revisa `Work_model.php` - Patrón de modelo  
✅ Revisa `WorkResource.php` - Patrón de Resource Filament  

### 3. Seguir el Checklist de Desarrollo (40-60 horas)

✅ Consulta `IMPLEMENTATION_CHECKLIST.md`  
✅ Crea 30+ modelos (copiar patrón de Work_model.php)  
✅ Crea 15+ Resources Filament (copiar patrón de WorkResource.php)  
✅ Implementa lógica de negocio  
✅ Crea dashboard ejecutivo  
✅ Agrega tests  

---

## 🛠️ TROUBLESHOOTING

### "Puerto 8000 ya está en uso"

```bash
# Cambiar puerto en docker-compose.yml
# Busca: ports: - "8000:8000"
# Cambia a: ports: - "8001:8000"

docker-compose up -d
# Accede a http://localhost:8001
```

### "Puerto 3306 ya está en uso"

```bash
# Cambiar puerto en docker-compose.yml
# Busca: ports: - "3306:3306"
# Cambia a: ports: - "3307:3306"
```

### "MySQL no se conecta"

```bash
# Verificar que MySQL está listo
docker-compose logs db

# Esperar 30 segundos y reintentar
docker-compose restart app

# Ver si hay errores
docker-compose logs -f app
```

### "Permiso denegado en storage"

```bash
# Dentro del contenedor
docker exec kdp-app bash
chmod -R 775 storage bootstrap/cache
exit
```

### "Error: "Base de datos no existe"

```bash
# Re-ejecutar migraciones
docker exec kdp-app php artisan migrate:fresh --seed --force

# O eliminar todo y empezar de nuevo
docker-compose down -v
docker-compose up -d
./deploy.sh
```

### "Error al instalar dependencias"

```bash
# Limpiar caché de Composer
docker exec kdp-app composer clear-cache

# Reinstalar
docker exec kdp-app composer install --no-cache
```

---

## 📈 MONITOREO

### Ver Logs en Tiempo Real

```bash
# Todos los logs
docker-compose logs -f

# Solo aplicación
docker-compose logs -f app

# Solo BD
docker-compose logs -f db

# Últimas 100 líneas
docker-compose logs --tail=100
```

### Estadísticas de Recursos

```bash
# Ver CPU/Memoria/Red
docker stats

# Más detalle
docker stats --all
```

### Inspeccionar Contenedores

```bash
# Ver configuración completa
docker inspect kdp-app

# Ver variables de entorno
docker exec kdp-app env

# Ver volúmenes montados
docker inspect kdp-app -f '{{json .Mounts}}' | python -m json.tool
```

---

## 🔐 SEGURIDAD EN DESARROLLO

⚠️ **IMPORTANTE: Solo para desarrollo local**

Si necesitas exponer al internet:

1. Cambiar contraseña admin:
```bash
docker exec kdp-app php artisan tinker
>>> User::find(1)->update(['password' => Hash::make('nueva-contraseña-fuerte')])
>>> exit
```

2. Cambiar APP_KEY:
```bash
# Generar nueva clave
docker exec kdp-app php artisan key:generate

# Actualizar .env
```

3. Deshabilitar debug mode:
```bash
# En .env, cambiar:
APP_DEBUG=false
```

4. Cambiar credenciales de BD:
```bash
# En .env, cambiar:
DB_PASSWORD=nueva-contraseña-fuerte
MYSQL_PASSWORD=nueva-contraseña-fuerte
```

---

## 📦 VOLÚMENES Y PERSISTENCIA

Los datos se guardan en:

```
kdp-mysql:          Base de datos MySQL
./storage/app:      Ficheros de usuarios (manuscritos, ilustraciones)
./public:           Assets compilados
./bootstrap/cache:  Caché de la aplicación
```

**Estos volúmenes persisten entre reinicios.**

Para limpiar TODO (perder datos):
```bash
docker-compose down -v
```

---

## 🔄 ACTUALIZAR CÓDIGO

Si actualizas el código en GitHub:

```bash
# 1. Traer cambios
git pull origin main

# 2. Reinstalar dependencias (si hay cambios en composer.json)
docker exec kdp-app composer install

# 3. Ejecutar migraciones (si hay nuevas)
docker exec kdp-app php artisan migrate

# 4. Compilar assets (si hay cambios en frontend)
docker exec kdp-app npm run build

# 5. Limpiar caché
docker exec kdp-app php artisan cache:clear
```

---

## 📊 ARQUITECTURA

```
┌─────────────────────────────────────────────────┐
│           TU COMPUTADORA                        │
├─────────────────────────────────────────────────┤
│  ┌─────────────────────────────────────────┐   │
│  │     Docker Desktop                      │   │
│  ├─────────────────────────────────────────┤   │
│  │  ┌──────────────┐  ┌──────────────┐   │   │
│  │  │  kdp-app    │  │  kdp-db      │   │   │
│  │  │ (PHP 8.2)   │  │ (MySQL 8.0)  │   │   │
│  │  └──────┬───────┘  └──────────────┘   │   │
│  │         │                              │   │
│  │  ┌──────▼──────────┐                  │   │
│  │  │  kdp-pma        │                  │   │
│  │  │ (PhpMyAdmin)    │                  │   │
│  │  └─────────────────┘                  │   │
│  └─────────────────────────────────────────┘   │
│           ▲         ▲         ▲                 │
│     :8000 │    :3306│    :8080│                │
│           │         │         │                │
├───────────┼─────────┼─────────┼─────────────┤
│  Navegador│  MySQL  │ PhpMyAdmin            │
└───────────┼─────────┼─────────┼─────────────┘
   http://localhost:8000/admin
```

---

## 🎓 APRENDIZAJE

Después de ver la aplicación en ejecución:

1. **Explorar Admin Panel**
   - Ver datos de ejemplo
   - Crear obra nueva
   - Editar datos

2. **Entender la Estructura**
   - Leer código de ejemplo
   - Ver relaciones en BD
   - Explorar migrations

3. **Crear Nuevos Modelos**
   - Copiar patrón de Work_model.php
   - Crear Series, Publication, etc.
   - Definir relaciones

4. **Crear Nueva Funcionalidad**
   - Crear nuevos Resources Filament
   - Personalizar formularios
   - Agregar validaciones

---

## 📞 SOPORTE

Si tienes problemas:

1. Revisa los logs: `docker-compose logs -f app`
2. Consulta TROUBLESHOOTING más arriba
3. Lee README.md
4. Revisa IMPLEMENTATION_CHECKLIST.md

---

## ✅ CHECKLIST DE DESPLIEGUE

- [ ] Docker y Docker Compose instalados
- [ ] Repositorio clonado
- [ ] Script deploy.sh ejecutado (o pasos manuales)
- [ ] 3 contenedores en ejecución
- [ ] Acceso a http://localhost:8000/admin
- [ ] Login con admin@kdpmanager.local
- [ ] Datos de ejemplo visibles
- [ ] PhpMyAdmin accesible
- [ ] Puedes crear nuevas obras
- [ ] Documentación leída

---

**¡Listo para comenzar! 🚀📚**

Próximo paso: `docker-compose up -d && ./deploy.sh`

O si ya está desplegado: `http://localhost:8000/admin`
