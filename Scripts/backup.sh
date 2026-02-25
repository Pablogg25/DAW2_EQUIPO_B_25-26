#!/bin/bash

# CONFIGURACIÓN
APP_NAME="LaCremallera"
APP_PATH="/var/www/DAW2_EQUIPO_B_25-26"
BACKEND_PATH="$APP_PATH/Backend/LaCremalleraAPI"
FRONTEND_PATH="$APP_PATH/Frontend/LaCremallera"
BACKUP_DIR="$APP_PATH/Backup"
DATE=$(date +"%Y%m%d_%H%M%S")
GIT_REPO="https://github.com/Pablogg25/DAW2_EQUIPO_B_25-26.git"
DB_NAME="la_cremallera"
DB_USER="admin"
DB_PASSWORD="admin"
PHP_VERSION="8.3"
SERVER_IP=$(curl -s ifconfig.me)

# Colores
GREEN="\033[0;32m"
RED="\033[0;31m"
WHITE="\033[0m"

# Crear directorio de backup si no existe
mkdir -p $BACKUP_DIR

# Copia de seguridad de los archivos del proyecto
BACKUP_NAME="backup_$APP_NAME_$DATE.tar.gz"
echo "Realizando copia de seguridad de los archivos del proyecto"
tar -czf $BACKUP_DIR/$BACKUP_NAME $APP_PATH || { echo -e "${RED}Error al realizar la copia de seguridad de los archivos.${WHITE}"; exit 1; }
echo -e "${GREEN}Copia de seguridad de los archivos realizada: $BACKUP_DIR/$BACKUP_NAME${WHITE}"

# Copia de seguridad de la base de datos
DB_BACKUP_NAME="backup_${DB_NAME}_$DATE.sql.gz"
echo "Realizando copia de seguridad de la base de datos"
mysqldump -u $DB_USER -p$DB_PASSWORD $