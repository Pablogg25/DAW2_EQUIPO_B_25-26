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

# Colores
GREEN="\033[0;32m"
RED="\033[0;31m"
WHITE="\033[0m"

echo -e " Instalación automática $APP_NAME"

# ACTUALIZAR SISTEMA
echo "Actualizando sistema"
sudo apt update || { echo -e "${RED}Error al actualizar el sistema.${WHITE}"; exit 1; }
sudo apt upgrade -y || { echo -e "${RED}Error al actualizar paquetes.${WHITE}"; exit 1; }

# INSTALAR NGINX
echo "Instalando Nginx"
sudo apt-get install -y nginx || { echo -e "${RED}Error al instalar Nginx.${WHITE}"; exit 1; }
sudo systemctl enable nginx
sudo systemctl start nginx || { echo -e "${RED}Error al iniciar Nginx.${WHITE}"; exit 1; }

# INSTALAR PHP 8.3
echo "Instalando PHP $PHP_VERSION"
sudo apt-get install -y php$PHP_VERSION php$PHP_VERSION-fpm php$PHP_VERSION-cli \
php$PHP_VERSION-mysql php$PHP_VERSION-xml php$PHP_VERSION-mbstring \
php$PHP_VERSION-curl php$PHP_VERSION-zip php$PHP_VERSION-bcmath unzip git curl || { echo -e "${RED}Error al instalar PHP.${WHITE}"; exit 1; }

sudo systemctl enable php$PHP_VERSION-fpm
sudo systemctl start php$PHP_VERSION-fpm || { echo -e "${RED}Error al iniciar PHP.${WHITE}"; exit 1; }

# INSTALAR COMPOSER
echo "Instalando Composer"
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# INSTALAR NODE
echo "Instalando Node.js"
curl -fsSL https://deb.nodesource.com/setup_lts.x | sudo -E bash -
sudo apt install nodejs -y || exit 1

# INSTALAR MYSQL
echo "Instalando MySQL"
sudo apt install mysql-server -y || exit 1
sudo systemctl enable mysql
sudo systemctl start mysql || { echo -e "${RED}Error al iniciar MySQL.${WHITE}"; exit 1; }

# CONFIGURAR BASE DE DATOS
echo "Verificando existencia de la base de datos"
DB_EXISTS=$(mysql -u $DB_USER -p$DB_PASSWORD -e "SHOW DATABASES LIKE '$DB_NAME';" | grep "$DB_NAME" > /dev/null; echo "$?")
if [ "$DB_EXISTS" -eq 0 ]; then
  echo -e "${GREEN}La base de datos $DB_NAME ya existe. No se creará nuevamente.${WHITE}"
else
  echo "Creando base de datos $DB_NAME"
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
  echo "El directorio $APP_PATH ya existe, lo eliminaremos para evitar conflictos."
  sudo rm -rf $APP_PATH
fi

git clone $GIT_REPO $APP_PATH || exit 1

# CONFIGURAR BACKEND (LARAVEL)
echo "Configurando Backend Laravel"
cd $BACKEND_PATH || exit 1

composer install --no-dev --optimize-autoloader || exit 1

cp .env.example .env

# Configurar la base de datos en Laravel
sed -i "s/DB_DATABASE=.*/DB_DATABASE=$DB_NAME/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=$DB_USER/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$DB_PASSWORD/" .env
sed -i "s/APP_ENV=.*/APP_ENV=production/" .env
sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" .env

php artisan key:generate

# Asegurarse de que los directorios tengan permisos correctos
sudo chown -R ubuntu:www-data $APP_PATH
sudo find $APP_PATH -type d -exec chmod 755 {} \;
sudo find $APP_PATH -type f -exec chmod 644 {} \;

php artisan migrate --force
php artisan migrate --seed --force

php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache

# CONFIGURAR FRONTEND
echo "Configurando Frontend React"
cd $FRONTEND_PATH || exit 1

npm install || exit 1
npm run build || exit 1

# CONFIGURAR NGINX
echo "Configurando Nginx"
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
echo -e " INSTALACIÓN COMPLETADA CON ÉXITO "
echo -e "${GREEN}Puedes acceder al frontend en:${WHITE}"
echo -e "http://$SERVER_IP"
echo -e "${GREEN}Y a la API en:${WHITE}"
echo -e "http://$SERVER_IP/api"
echo -e "${WHITE}¡Recuerda revisar los logs si algo no funciona correctamente!"