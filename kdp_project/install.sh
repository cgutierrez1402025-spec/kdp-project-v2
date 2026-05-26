#!/bin/bash

# 🚀 KDP Author Manager - Script de Instalación Asistida

echo "╔════════════════════════════════════════════════════════════╗"
echo "║      KDP AUTHOR MANAGER - INSTALACIÓN ASISTIDA             ║"
echo "╚════════════════════════════════════════════════════════════╝"
echo ""

# Colores
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Función para imprimir secciones
section() {
    echo -e "\n${YELLOW}▶ $1${NC}"
}

# Función para éxito
success() {
    echo -e "${GREEN}✓ $1${NC}"
}

# Función para error
error() {
    echo -e "${RED}✗ $1${NC}"
    exit 1
}

# Verificar si estamos en Docker o local
read -p "¿Usar Docker? (s/n): " use_docker

if [ "$use_docker" == "s" ] || [ "$use_docker" == "S" ]; then
    section "Instalación con Docker"
    
    if ! command -v docker &> /dev/null; then
        error "Docker no está instalado. Instálalo desde https://www.docker.com"
    fi
    
    success "Docker detectado"
    
    # Copiar .env
    if [ ! -f ".env" ]; then
        section "Copiando archivo .env"
        cp .env.example .env
        success "Archivo .env creado"
    fi
    
    # Construir y levantar contenedores
    section "Construyendo contenedores..."
    docker-compose up -d
    
    success "Contenedores ejecutándose"
    
    sleep 2
    
    # Instalar dependencias
    section "Instalando dependencias de Composer..."
    docker exec kdp-app composer install
    success "Dependencias de Composer instaladas"
    
    section "Instalando dependencias de npm..."
    docker exec kdp-app npm install
    success "Dependencias de npm instaladas"
    
    # Generar clave
    section "Generando clave de aplicación..."
    docker exec kdp-app php artisan key:generate
    success "Clave generada"
    
    # Ejecutar migraciones
    section "Ejecutando migraciones..."
    docker exec kdp-app php artisan migrate --seed
    success "Migraciones y seeders completados"
    
    # Compilar assets
    section "Compilando assets..."
    docker exec kdp-app npm run build
    success "Assets compilados"
    
    # Crear enlace simbólico
    section "Creando enlace de storage..."
    docker exec kdp-app php artisan storage:link
    success "Storage enlazado"
    
    echo ""
    echo "╔════════════════════════════════════════════════════════════╗"
    echo "║                  ✓ INSTALACIÓN COMPLETADA                  ║"
    echo "╠════════════════════════════════════════════════════════════╣"
    echo "║  App:       http://localhost:8000                          ║"
    echo "║  Admin:     http://localhost:8000/admin                    ║"
    echo "║  PhpMyAdmin: http://localhost:8080                         ║"
    echo "║                                                            ║"
    echo "║  Credenciales por defecto:                                 ║"
    echo "║  Email:    admin@kdpmanager.local                         ║"
    echo "║  Password: password                                        ║"
    echo "╚════════════════════════════════════════════════════════════╝"
    
else
    section "Instalación Local"
    
    # Verificar Composer
    if ! command -v composer &> /dev/null; then
        error "Composer no está instalado. Instálalo desde https://getcomposer.org"
    fi
    success "Composer detectado"
    
    # Verificar PHP
    if ! command -v php &> /dev/null; then
        error "PHP no está instalado. Se requiere PHP 8.2+"
    fi
    
    php_version=$(php -r 'echo PHP_VERSION;')
    success "PHP $php_version detectado"
    
    # Verificar MySQL
    if ! command -v mysql &> /dev/null; then
        error "MySQL no está instalado. Instálalo desde https://www.mysql.com"
    fi
    success "MySQL detectado"
    
    # Copiar .env
    if [ ! -f ".env" ]; then
        section "Copiando archivo .env"
        cp .env.example .env
        success "Archivo .env creado"
        
        echo ""
        echo "⚠ Edita .env con tus datos de MySQL:"
        echo "   - DB_HOST"
        echo "   - DB_DATABASE"
        echo "   - DB_USERNAME"
        echo "   - DB_PASSWORD"
    fi
    
    # Crear base de datos
    read -p "¿Crear base de datos? (s/n): " create_db
    
    if [ "$create_db" == "s" ] || [ "$create_db" == "S" ]; then
        read -p "Usuario MySQL (por defecto: root): " mysql_user
        mysql_user=${mysql_user:-root}
        
        read -sp "Contraseña MySQL: " mysql_pass
        echo ""
        
        mysql -u "$mysql_user" -p"$mysql_pass" -e "CREATE DATABASE IF NOT EXISTS kdp_author_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
        
        if [ $? -eq 0 ]; then
            success "Base de datos creada"
        else
            error "Error al crear la base de datos"
        fi
    fi
    
    # Instalar dependencias
    section "Instalando dependencias de Composer..."
    composer install
    
    if [ $? -eq 0 ]; then
        success "Dependencias instaladas"
    else
        error "Error al instalar Composer"
    fi
    
    section "Instalando dependencias de npm..."
    npm install
    
    if [ $? -eq 0 ]; then
        success "Dependencias npm instaladas"
    else
        error "Error al instalar npm"
    fi
    
    # Generar clave
    section "Generando clave de aplicación..."
    php artisan key:generate
    success "Clave generada"
    
    # Ejecutar migraciones
    section "Ejecutando migraciones..."
    php artisan migrate --seed
    
    if [ $? -eq 0 ]; then
        success "Migraciones completadas"
    else
        error "Error en migraciones"
    fi
    
    # Compilar assets
    section "Compilando assets..."
    npm run build
    success "Assets compilados"
    
    # Crear enlace simbólico
    section "Creando enlace de storage..."
    php artisan storage:link
    success "Storage enlazado"
    
    echo ""
    echo "╔════════════════════════════════════════════════════════════╗"
    echo "║                  ✓ INSTALACIÓN COMPLETADA                  ║"
    echo "╠════════════════════════════════════════════════════════════╣"
    echo "║  Comandos útiles:                                          ║"
    echo "║                                                            ║"
    echo "║  Iniciar servidor:                                         ║"
    echo "║    php artisan serve                                       ║"
    echo "║                                                            ║"
    echo "║  Accede a: http://localhost:8000/admin                    ║"
    echo "║                                                            ║"
    echo "║  Credenciales por defecto:                                 ║"
    echo "║  Email:    admin@kdpmanager.local                         ║"
    echo "║  Password: password                                        ║"
    echo "╚════════════════════════════════════════════════════════════╝"
fi

echo ""
success "¡Instalación completada exitosamente!"
