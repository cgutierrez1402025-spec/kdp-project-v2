#!/bin/bash

# 🚀 KDP AUTHOR MANAGER - SCRIPT DE DESPLIEGUE COMPLETO

set -e

echo "╔════════════════════════════════════════════════════════════╗"
echo "║                                                            ║"
echo "║     🚀 KDP AUTHOR MANAGER - DESPLIEGUE COMPLETO           ║"
echo "║                                                            ║"
echo "║     Aplicación Laravel profesional para autoedición       ║"
echo "║                                                            ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Colores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
BLUE='\033[0;34m'
NC='\033[0m'

# Funciones
section() {
    echo -e "\n${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
    echo -e "${YELLOW}▶ $1${NC}"
    echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"
}

success() {
    echo -e "${GREEN}✓ $1${NC}"
}

error() {
    echo -e "${RED}✗ $1${NC}"
    exit 1
}

info() {
    echo -e "${BLUE}ℹ $1${NC}"
}

# Verificar si estamos usando Docker
section "Verificando Requisitos"

if ! command -v docker &> /dev/null; then
    error "Docker no está instalado. Instálalo desde https://www.docker.com"
fi
success "Docker instalado"

if ! command -v docker-compose &> /dev/null; then
    error "Docker Compose no está instalado"
fi
success "Docker Compose instalado"

# Verificar que estamos en el directorio correcto
if [ ! -f "docker-compose.yml" ]; then
    error "docker-compose.yml no encontrado. ¿Estás en el directorio correcto?"
fi
success "Directorio correcto verificado"

# Crear archivos necesarios
section "Preparando Archivos de Configuración"

if [ ! -f ".env" ]; then
    if [ -f ".env.local" ]; then
        cp .env.local .env
        success "Archivo .env creado desde .env.local"
    elif [ -f ".env.example" ]; then
        cp .env.example .env
        success "Archivo .env creado desde .env.example"
    else
        error "No se encontró .env.example o .env.local"
    fi
else
    success "Archivo .env ya existe"
fi

# Verificar Docker Compose
section "Verificando Docker Compose"

if [ ! -f "docker-compose.override.yml" ]; then
    info "docker-compose.override.yml no encontrado. Se usará docker-compose.yml"
else
    success "docker-compose.override.yml encontrado"
fi

# Detener contenedores previos
section "Limpiando Contenedores Previos"

echo "Deteniendo contenedores previos..."
docker-compose down 2>/dev/null || true
success "Contenedores previos detenidos"

# Construir y levantar contenedores
section "Construyendo y Levantando Contenedores"

echo "Esto puede tomar unos minutos la primera vez..."
docker-compose up -d

# Esperar a que la BD esté lista
section "Esperando a que la Base de Datos esté Lista"

echo "Esperando 15 segundos para que MySQL inicie..."
sleep 15

# Verificar que los contenedores están corriendo
if ! docker ps | grep -q kdp-app; then
    error "El contenedor kdp-app no se inició correctamente"
fi
success "Contenedor kdp-app en ejecución"

if ! docker ps | grep -q kdp-db; then
    error "El contenedor kdp-db no se inició correctamente"
fi
success "Contenedor kdp-db en ejecución"

if ! docker ps | grep -q kdp-pma; then
    error "El contenedor kdp-pma no se inició correctamente"
fi
success "Contenedor kdp-pma en ejecución"

# Instalar dependencias
section "Instalando Dependencias de Composer"

docker exec kdp-app composer install --no-interaction --prefer-dist

if [ $? -eq 0 ]; then
    success "Dependencias de Composer instaladas"
else
    error "Error al instalar dependencias de Composer"
fi

# Generar clave de aplicación
section "Generando Clave de Aplicación"

docker exec kdp-app php artisan key:generate --force
success "Clave de aplicación generada"

# Ejecutar migraciones
section "Ejecutando Migraciones y Seeders"

echo "Esto puede tomar un momento..."
docker exec kdp-app php artisan migrate:fresh --seed --force

if [ $? -eq 0 ]; then
    success "Migraciones y seeders completados"
else
    error "Error al ejecutar migraciones"
fi

# Crear enlace simbólico para storage
section "Configurando Almacenamiento"

docker exec kdp-app php artisan storage:link
success "Enlace de storage creado"

# Compilar assets
section "Compilando Assets (Opcional)"

docker exec kdp-app npm install 2>/dev/null || true
docker exec kdp-app npm run build 2>/dev/null || true
success "Assets compilados"

# Limpiar caché
section "Limpiando Caché"

docker exec kdp-app php artisan cache:clear
docker exec kdp-app php artisan config:clear
docker exec kdp-app php artisan route:clear
docker exec kdp-app php artisan view:clear
success "Caché limpiado"

# Crear usuario admin
section "Verificando Usuario Admin"

echo "Verificando si existe usuario admin..."
ADMIN_EXISTS=$(docker exec kdp-app php artisan tinker --execute "echo User::where('email', 'admin@kdpmanager.local')->count();" 2>/dev/null || echo "0")

if [ "$ADMIN_EXISTS" = "0" ] || [ "$ADMIN_EXISTS" = "" ]; then
    info "Creando usuario admin..."
    docker exec -i kdp-app php artisan make:filament-user <<EOF
admin
admin@kdpmanager.local
password
password
y
EOF
    success "Usuario admin creado"
else
    success "Usuario admin ya existe"
fi

# Resumen final
section "✅ DESPLIEGUE COMPLETADO"

echo ""
echo -e "${GREEN}╔════════════════════════════════════════════════════════════╗${NC}"
echo -e "${GREEN}║                  ¡LISTO PARA USAR! 🚀                     ║${NC}"
echo -e "${GREEN}╚════════════════════════════════════════════════════════════╝${NC}"
echo ""
echo -e "${YELLOW}🌐 URLs DE ACCESO:${NC}"
echo ""
echo -e "  📱 ${BLUE}Aplicación:${NC}  http://localhost:8000"
echo -e "  🔐 ${BLUE}Admin Panel:${NC} http://localhost:8000/admin"
echo -e "  📊 ${BLUE}PhpMyAdmin:${NC}  http://localhost:8080"
echo ""
echo -e "${YELLOW}🔑 CREDENCIALES:${NC}"
echo ""
echo -e "  ${BLUE}Email:${NC}    admin@kdpmanager.local"
echo -e "  ${BLUE}Password:${NC} password"
echo ""
echo -e "${YELLOW}💾 INFORMACIÓN DE BD:${NC}"
echo ""
echo -e "  ${BLUE}Host:${NC}     db (localhost:3306)"
echo -e "  ${BLUE}Database:${NC} kdp_author_manager"
echo -e "  ${BLUE}User:${NC}     kdp_user"
echo -e "  ${BLUE}Password:${NC} kdp_password"
echo ""
echo -e "${YELLOW}🐳 CONTENEDORES EN EJECUCIÓN:${NC}"
echo ""
docker ps --filter "label!=null" --format "table {{.Names}}\t{{.Image}}\t{{.Status}}\t{{.Ports}}" 2>/dev/null || docker ps --format "table {{.Names}}\t{{.Image}}\t{{.Status}}\t{{.Ports}}" | grep kdp
echo ""
echo -e "${YELLOW}📝 PRÓXIMOS PASOS:${NC}"
echo ""
echo "  1. Abre en tu navegador: http://localhost:8000/admin"
echo "  2. Inicia sesión con:"
echo "     Email: admin@kdpmanager.local"
echo "     Password: password"
echo "  3. Explora los datos de ejemplo"
echo "  4. Lee la documentación en README.md"
echo "  5. Sigue el IMPLEMENTATION_CHECKLIST.md"
echo ""
echo -e "${YELLOW}🛠️ COMANDOS ÚTILES:${NC}"
echo ""
echo "  Ver logs:         docker-compose logs -f app"
echo "  Acceder a bash:   docker exec -it kdp-app bash"
echo "  Ejecutar tinker:  docker exec -it kdp-app php artisan tinker"
echo "  Parar servicios:  docker-compose down"
echo "  Reiniciar:        docker-compose restart"
echo ""
echo -e "${YELLOW}⚠️  CAMBIAR CREDENCIALES EN PRODUCCIÓN${NC}"
echo ""
echo -e "${GREEN}¡Disfruta tu aplicación! 📚✨${NC}"
echo ""
