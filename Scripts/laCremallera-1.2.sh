#!/bin/bash

set -e

# CONFIGURACIÓN
APP_NAME="LaCremallera"
APP_PATH="/var/www/DAW2_EQUIPO_B_25-26"
BACKEND_PATH="$APP_PATH/Backend/LaCremalleraAPI"
FRONTEND_PATH="$APP_PATH/Frontend/LaCremallera"
GIT_REPO="https://github.com/Pablogg25/DAW2_EQUIPO_B_25-26.git"

DB_NAME="la_cremallera"
DB_USER="admin"
DB_PASSWORD="admin"

PHP_VERSION="8.3"
SERVER_IP=$(curl -s ifconfig.me)

GREEN="\033[0;32m"
RED="\033[0;31m"
WHITE="\033[0m"

echo -e " Instalación automática $APP_NAME"

# ACTUALIZAR SISTEMA
echo "Actualizando sistema"
sudo apt update
sudo apt upgrade -y

# INSTALAR NGINX
echo "Instalando Nginx"
sudo apt install -y nginx
sudo systemctl enable nginx
sudo systemctl start nginx

# INSTALAR PHP
echo "Instalando PHP $PHP_VERSION"
sudo apt install -y php$PHP_VERSION php$PHP_VERSION-fpm php$PHP_VERSION-cli \
php$PHP_VERSION-mysql php$PHP_VERSION-xml php$PHP_VERSION-mbstring \
php$PHP_VERSION-curl php$PHP_VERSION-zip php$PHP_VERSION-bcmath unzip git curl

sudo systemctl enable php$PHP_VERSION-fpm
sudo systemctl start php$PHP_VERSION-fpm

# INSTALAR COMPOSER
echo "Instalando Composer"
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# INSTALAR NODE
echo "Instalando Node.js"
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt install nodejs -y

# INSTALAR MYSQL
echo "Instalando MySQL"
sudo apt install mysql-server -y
sudo systemctl enable mysql
sudo systemctl start mysql

# CONFIGURAR BASE DE DATOS
echo "Configurando base de datos"

DB_EXISTS=$(sudo mysql -e "SHOW DATABASES LIKE '$DB_NAME';" | grep "$DB_NAME" || true)

if [ "$DB_EXISTS" = "$DB_NAME" ]; then
  echo -e "${GREEN}La base de datos ya existe.${WHITE}"
else
  echo "Creando base de datos y usuario..."

  sudo mysql <<EOF
CREATE DATABASE $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
EOF

fi

# CLONAR REPOSITORIO
echo "Clonando repositorio"

if [ -d "$APP_PATH" ]; then
  echo "Eliminando instalación anterior..."
  sudo rm -rf $APP_PATH
fi

git clone $GIT_REPO $APP_PATH

# CONFIGURAR BACKEND
echo "Configurando Backend Laravel"

cd $BACKEND_PATH

composer install --no-dev --optimize-autoloader

# Crear .env solo si no existe
if [ ! -f ".env" ]; then
  cp .env.example .env
fi

# Configurar variables
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
sed -i "s/APP_ENV=.*/APP_ENV=production/" .env
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env

php artisan key:generate

# PERMISOS
sudo chown -R www-data:www-data $APP_PATH
sudo find $APP_PATH -type d -exec chmod 755 {} \;
sudo find $APP_PATH -type f -exec chmod 644 {} \;

# MIGRACIONES
php artisan migrate --force
php artisan db:seed --force

php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# CONFIGURAR FRONTEND
echo "Configurando Frontend React"

cd $FRONTEND_PATH

npm install
npm run build

# CONFIGURAR NGINX
echo "Configurando Nginx"

NGINX_CONF="/etc/nginx/sites-available/$APP_NAME"

sudo tee $NGINX_CONF > /dev/null <<EOL
server {
    listen 80;
    server_name $SERVER_IP;

    root $FRONTEND_PATH/dist;
    index index.html;

    location / {
        try_files \$uri \$uri/ /index.html;
    }

    location /api {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        root $BACKEND_PATH/public;
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/run/php/php$PHP_VERSION-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }

    error_log /var/log/nginx/${APP_NAME}_error.log;
    access_log /var/log/nginx/${APP_NAME}_access.log;
}
EOL

sudo ln -sf $NGINX_CONF /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default

sudo nginx -t
sudo systemctl restart nginx

echo -e "${GREEN} INSTALACIÓN COMPLETADA ${WHITE}"
echo -e "Frontend: http://$SERVER_IP"
echo -e "API: http://$SERVER_IP/api"