#!/bin/bash

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

echo "=============================="
echo " Instalación automática $APP_NAME"
echo "=============================="

# ACTUALIZAR SISTEMA
echo "Actualizando sistema..."
sudo apt update && sudo apt upgrade -y || exit 1

# INSTALAR NGINX
echo "Instalando Nginx..."
sudo apt install nginx -y || exit 1
sudo systemctl enable nginx
sudo systemctl start nginx

# INSTALAR PHP 8.3
echo "Instalando PHP $PHP_VERSION..."
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

sudo apt install php$PHP_VERSION php$PHP_VERSION-fpm php$PHP_VERSION-cli \
php$PHP_VERSION-mysql php$PHP_VERSION-xml php$PHP_VERSION-mbstring \
php$PHP_VERSION-curl php$PHP_VERSION-zip php$PHP_VERSION-bcmath unzip git curl -y \
|| exit 1

sudo systemctl enable php$PHP_VERSION-fpm
sudo systemctl start php$PHP_VERSION-fpm

# INSTALAR COMPOSER
echo "Instalando Composer..."
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# INSTALAR NODE (LTS)
echo "Instalando Node.js LTS..."
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt install nodejs -y || exit 1

# INSTALAR MYSQL
echo "Instalando MySQL..."
sudo apt install mysql-server -y || exit 1
sudo systemctl enable mysql
sudo systemctl start mysql

# CONFIGURAR BASE DE DATOS
echo "Configurando base de datos..."

sudo mysql <<EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
EOF

# CLONAR REPOSITORIO
echo "Clonando repositorio..."

sudo rm -rf $APP_PATH
sudo git clone $GIT_REPO $APP_PATH || exit 1

sudo chown -R ubuntu:www-data $APP_PATH
sudo chmod -R 775 $APP_PATH

# CONFIGURAR BACKEND (LARAVEL)
echo "Configurando Backend Laravel..."

cd $BACKEND_PATH || exit 1

composer install --no-dev --optimize-autoloader || exit 1

cp .env.example .env

sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
sed -i "s/APP_ENV=.*/APP_ENV=production/" .env
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env

php artisan key:generate

sudo chmod -R 775 storage bootstrap/cache

php artisan migrate --force
php artisan migrate --seed --force

php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# CONFIGURAR FRONTEND (REACT + VITE)
echo "Configurando Frontend React..."

cd $FRONTEND_PATH || exit 1

sudo chown -R ubuntu:www-data $FRONTEND_PATH

npm install || exit 1
npm run build || exit 1

# CONFIGURAR NGINX
echo "Configurando Nginx..."

NGINX_CONF="/etc/nginx/sites-available/$APP_NAME"

sudo tee $NGINX_CONF > /dev/null <<EOL
server {
    listen 80;
    server_name $SERVER_IP;

    # FRONTEND
    root $FRONTEND_PATH/dist;
    index index.html;

    location / {
        try_files \$uri \$uri/ /index.html;
    }

    # API LARAVEL
    location /api {
        root $BACKEND_PATH/public;
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
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

sudo nginx -t || exit 1
sudo systemctl restart nginx

# FINAL
echo " INSTALACIÓN COMPLETADA"
echo "Frontend:"
echo "http://$SERVER_IP"
echo "API:"
echo "http://$SERVER_IP/api"