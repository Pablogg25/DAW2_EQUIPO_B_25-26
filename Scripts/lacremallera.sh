#!/bin/bash

# Configuración de las variables
APP_NAME="LaCremallera"
APP_PATH="/var/www/$APP_NAME"
BACKEND_PATH="$APP_PATH/Backend/LaCremalleraAPI"

GIT_REPO="https://github.com/Pablogg25/DAW2_EQUIPO_B_25-26.git"

DB_NAME="la_cremallera"
DB_USER="admin"
DB_PASSWORD="admin"

PHP_VERSION="8.2"
SERVER_IP=$(curl -s ifconfig.me)

# Script de instalación de Laravel API con Nginx

echo "Iniciando instalación de Laravel API con Nginx..."

# Actualizar sistema
sudo apt update && sudo apt upgrade -y
if [ $? -eq 0 ]; then
    echo "Sistema actualizado correctamente"
else
    echo "Error al actualizar sistema"
    exit 1
fi

# Instalar Nginx
sudo apt install nginx -y
if [ $? -eq 0 ]; then
    sudo systemctl enable nginx
    sudo systemctl start nginx
    echo "Nginx instalado y arrancado"
else
    echo "Error al instalar Nginx"
    exit 1
fi

# Instalar PHP + extensiones
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
sudo apt install php$PHP_VERSION php$PHP_VERSION-fpm php$PHP_VERSION-cli \
php$PHP_VERSION-mysql php$PHP_VERSION-xml php$PHP_VERSION-mbstring \
php$PHP_VERSION-curl php$PHP_VERSION-zip php$PHP_VERSION-bcmath unzip git -y

if [ $? -eq 0 ]; then
    sudo systemctl enable php$PHP_VERSION-fpm
    sudo systemctl start php$PHP_VERSION-fpm
    echo "PHP $PHP_VERSION instalado y PHP-FPM arrancado"
else
    echo "Error al instalar PHP"
    exit 1
fi

# Instalar Composer
cd ~
curl -sS https://getcomposer.org/installer | php
if [ $? -eq 0 ]; then
    sudo mv composer.phar /usr/local/bin/composer
    echo "Composer instalado correctamente"
else
    echo "Error al instalar Composer"
    exit 1
fi

# Instalar MySQL
sudo apt install mysql-server -y
if [ $? -eq 0 ]; then
    sudo systemctl enable mysql
    sudo systemctl start mysql
    echo "MySQL instalado y arrancado"
else
    echo "Error al instalar MySQL"
    exit 1
fi

# Configurar base de datos
sudo mysql <<EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'%' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'%';
FLUSH PRIVILEGES;
EOF

if [ $? -eq 0 ]; then
    echo "Base de datos y usuario creados"
else
    echo "Error al crear base de datos o usuario"
    exit 1
fi

# Clonar repositorio
sudo git clone $GIT_REPO /var/www/
if [ $? -eq 0 ]; then
    echo "Repositorio clonado correctamente"
else
    echo "Error al clonar repositorio"
    exit 1
fi

# Permisos
sudo chown -R ubuntu:ubuntu $APP_PATH
sudo chmod -R 775 $BACKEND_PATH/storage $BACKEND_PATH/bootstrap/cache
if [ $? -eq 0 ]; then
    echo "Permisos configurados correctamente"
else
    echo "Error al configurar permisos"
    exit 1
fi

# Laravel setup
cd $BACKEND_PATH
composer install --no-dev --optimize-autoloader
if [ $? -eq 0 ]; then
    echo "Dependencias de Laravel instaladas"
else
    echo "Error al instalar dependencias de Laravel"
    exit 1
fi

# Migracion de las tablas y datos de prueba
php artisan migrate --force
php artisan migrate --seed

php artisan storage:link
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

if [ $? -eq 0 ]; then
    echo "Laravel configurado correctamente"
else
    echo "Error en configuración de Laravel"
    exit 1
fi

# Configurar Nginx para Laravel
NGINX_CONF="/etc/nginx/sites-available/$APP_NAME"

sudo tee $NGINX_CONF > /dev/null <<EOL
server {
    listen 80;
    server_name $SERVER_IP;

    root $BACKEND_PATH/public;

    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php$PHP_VERSION-fpm.sock;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    error_log /var/log/nginx/${APP_NAME}_error.log;
    access_log /var/log/nginx/${APP_NAME}_access.log;
}
EOL

sudo ln -s $NGINX_CONF /etc/nginx/sites-enabled/
sudo nginx -t
if [ $? -eq 0 ]; then
    sudo systemctl restart nginx
    echo "Nginx configurado correctamente"
else
    echo "Error en la configuración de Nginx"
    exit 1
fi

echo "Instalación completada con éxito"
echo "API disponible en: http://$SERVER_IP"
