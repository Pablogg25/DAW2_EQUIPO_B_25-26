#!/bin/bash

DB_NAME="la_cremallera"
DB_USER="admin"
DB_PASS="admin"

echo "Creando base de datos y usuario..."

sudo mysql <<EOF
CREATE DATABASE IF NOT EXISTS ${DB_NAME}
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS '${DB_USER}'@'%'
IDENTIFIED BY '${DB_PASS}';

GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'%';
FLUSH PRIVILEGES;
EOF

echo "Base de datos y usuario creados correctamente!!!"
