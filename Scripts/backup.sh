#!/bin/bash

# CONFIGURACIÓN
APP_NAME="LaCremallera"
APP_PATH="/var/www/DAW2_EQUIPO_B_25-26"
BACKEND_PATH="$APP_PATH/Backend/LaCremalleraAPI"
FRONTEND_PATH="$APP_PATH/Frontend/LaCremallera"
BACKUP_DIR="$APP_PATH/Backup"
DATE=$(date +"%Y%m%d_%H%M%S")

DB_NAME="la_cremallera"
DB_USER="admin"
DB_PASSWORD="admin"

PHP_VERSION="8.3"
SERVER_IP=$(curl -s ifconfig.me)

# Colores
GREEN="\033[0;32m"
RED="\033[0;31m"
WHITE="\033[0m"

echo "======================================"
echo " Iniciando backup de $APP_NAME"
echo " Fecha: $DATE"
echo "======================================"

# Crear directorio de backup si no existe
mkdir -p "$BACKUP_DIR"

# ==============================
# BACKUP DE ARCHIVOS
# ==============================

BACKUP_NAME="backup_${APP_NAME}_${DATE}.tar.gz"

echo "Realizando copia de seguridad de los archivos del proyecto..."

tar --exclude="$BACKUP_DIR" -czf "$BACKUP_DIR/$BACKUP_NAME" "$APP_PATH"

if [ $? -ne 0 ]; then
    echo -e "${RED}Error al realizar la copia de seguridad de los archivos.${WHITE}"
    exit 1
fi

echo -e "${GREEN}Backup de archivos creado:${WHITE} $BACKUP_DIR/$BACKUP_NAME"

# ==============================
# BACKUP BASE DE DATOS
# ==============================

DB_BACKUP_NAME="backup_${DB_NAME}_${DATE}.sql.gz"

echo "Realizando copia de seguridad de la base de datos..."

mysqldump -u "$DB_USER" -p"$DB_PASSWORD" "$DB_NAME" | gzip > "$BACKUP_DIR/$DB_BACKUP_NAME"

if [ $? -ne 0 ]; then
    echo -e "${RED}Error al realizar la copia de seguridad de la base de datos.${WHITE}"
    exit 1
fi

echo -e "${GREEN}Backup de base de datos creado:${WHITE} $BACKUP_DIR/$DB_BACKUP_NAME"

# ==============================
# LIMPIAR BACKUPS ANTIGUOS
# ==============================

echo "Eliminando backups antiguos (más de 7 días)..."

find "$BACKUP_DIR" -type f -mtime +7 -delete

echo -e "${GREEN}Backups antiguos eliminados correctamente.${WHITE}"

# ==============================
# FINAL
# ==============================

echo "======================================"
echo -e "${GREEN} Backup completado correctamente ${WHITE}"
echo " Archivos: $BACKUP_DIR/$BACKUP_NAME"
echo " Base de datos: $BACKUP_DIR/$DB_BACKUP_NAME"
echo "======================================"