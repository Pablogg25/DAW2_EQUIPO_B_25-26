#!/bin/bash

set -e

# CONFIGURACIÓN
APP_NAME="LaCremallera"
APP_PATH="/var/www/DAW2_EQUIPO_B_25-26"
BACKUP_DIR="$APP_PATH/Backup"

DB_NAME="la_cremallera"
DB_USER="admin"
DB_PASSWORD="admin"

# Colores
GREEN="\033[0;32m"
RED="\033[0;31m"
WHITE="\033[0m"

echo "======================================"
echo " Restaurando copia de seguridad $APP_NAME"
echo "======================================"

# Verificar que existe carpeta Backup
if [ ! -d "$BACKUP_DIR" ]; then
    echo -e "${RED}No existe la carpeta Backup${WHITE}"
    exit 1
fi

# Buscar último backup de archivos
LAST_FILE_BACKUP=$(ls -t "$BACKUP_DIR"/backup_${APP_NAME}_*.tar.gz 2>/dev/null | head -n 1)

# Buscar último backup de base de datos
LAST_DB_BACKUP=$(ls -t "$BACKUP_DIR"/backup_${DB_NAME}_*.sql.gz 2>/dev/null | head -n 1)

if [ -z "$LAST_FILE_BACKUP" ] || [ -z "$LAST_DB_BACKUP" ]; then
    echo -e "${RED}No se encontraron backups válidos${WHITE}"
    exit 1
fi

echo -e "${GREEN}Backup de archivos encontrado:${WHITE}"
echo "$LAST_FILE_BACKUP"

echo -e "${GREEN}Backup de base de datos encontrado:${WHITE}"
echo "$LAST_DB_BACKUP"

# =========================
# RESTAURAR ARCHIVOS
# =========================

echo "Eliminando versión actual del proyecto..."

find "$APP_PATH" -mindepth 1 -maxdepth 1 ! -name "Backup" -exec rm -rf {} +

echo "Restaurando archivos del proyecto..."

tar -xzf "$LAST_FILE_BACKUP" -C / || {
    echo -e "${RED}Error al restaurar los archivos del proyecto.${WHITE}"
    exit 1
}

echo -e "${GREEN}Archivos restaurados correctamente${WHITE}"

# =========================
# RESTAURAR BASE DE DATOS
# =========================

echo "Restaurando base de datos..."

gunzip < "$LAST_DB_BACKUP" | mysql -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" || {
    echo -e "${RED}Error al restaurar la base de datos.${WHITE}"
    exit 1
}

echo -e "${GREEN}Base de datos restaurada correctamente${WHITE}"

# =========================
# PERMISOS
# =========================

echo "Ajustando permisos..."

chown -R ubuntu:www-data "$APP_PATH"
chmod -R 775 "$APP_PATH"

echo "======================================"
echo -e "${GREEN} RESTAURACIÓN COMPLETADA ${WHITE}"
echo "======================================"