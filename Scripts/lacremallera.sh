#!/bin/bash

### ===== CONFIGURACIÓN =====
APP_NAME="LaCremalleraAPI"
APP_PATH="/var/www/LaCremalleraAPI"
BACKEND_PATH="$APP_PATH/backend"

GIT_REPO="https://github.com/USUARIO/LaCremalleraAPI.git"

DB_NAME="la_cremallera"
DB_USER="laravel"
DB_PASSWORD="password_seguro"

PHP_VERSION="8.1"
SERVER_IP=$(curl -s ifconfig.me)

### =========================

echo "🚀 Iniciando instalación de Laravel API..."

# 1️⃣ Actualizar sistema
sudo apt update && sudo apt upgrade -y

# 2️⃣ Apache
sudo apt install apache2 -y
sudo systemctl enable apache2
sudo systemctl start apache2

# 3️⃣ PHP + extensiones
sudo apt install software-properties-common -y
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

sudo apt install php$PHP_VERSION php$PHP_VERSION-cli php$PHP_VERSION-common \
php$PHP_VERSION-mysql php$PHP_VERSION-xml php$PHP_VERSION-mbstring \
php$PHP_VERSION-curl php$PHP_VERSION-zip php$PHP_VERSION-bcmath \
libapache2-mod-php$PHP_VERSION unzip git -y

# 4️⃣ Composer
cd ~
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# 5️⃣ MySQL
sudo apt install mysql-server -y
sudo systemctl enable mysql
sudo systemctl start mysql

sudo mysql <<EOF
CREATE DATABASE IF NOT EXISTS $DB_NAME;
CREATE USER IF NOT EXISTS '$DB_USER'@'localhost' IDENTIFIED BY '$DB_PASSWORD';
GRANT ALL PRIVILEGES ON $DB_NAME.* TO '$DB_USER'@'localhost';
FLUSH PRIVILEGES;
EOF

# 6️⃣ Clonar repositorio
sudo git clone $GIT_REPO /var/www/$APP_NAME

# 7️⃣ Permisos
sudo chown -R www-data:www-data /var/www/$APP_NAME
sudo chmod -R 775 $BACKEND_PATH/storage $BACKEND_PATH/bootstrap/cache

# 8️⃣ Laravel setup
cd $BACKEND_PATH
composer install --no-dev --optimize-autoloader

cp .env.example .env

sed -i "s|APP_ENV=.*|APP_ENV=production|" .env
sed -i "s|APP_DEBUG=.*|APP_DEBUG=false|" .env
sed -i "s|APP_URL=.*|APP_URL=http://$SERVER_IP|" .env

sed -i "s|DB_DATABASE=.*|DB_DATABASE=$DB_NAME|" .env
sed -i "s|DB_USERNAME=.*|DB_USERNAME=$DB_USER|" .env
sed -i "s|DB_PASSWORD=.*|DB_PASSWORD=$DB_PASSWORD|" .env

php artisan key:generate
php artisan migrate --force
php artisan storage:link

php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 9️⃣ Apache VirtualHost
sudo tee /etc/apache2/sites-available/laravel.conf > /dev/null <<EOL
<VirtualHost *:80>
    ServerName $SERVER_IP
    DocumentRoot $BACKEND_PATH/public

    <Directory $BACKEND_PATH/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/laravel_error.log
    CustomLog \${APACHE_LOG_DIR}/laravel_access.log combined
</VirtualHost>
EOL

sudo a2ensite laravel
sudo a2enmod rewrite
sudo systemctl restart apache2

echo "✅ Instalación completada"
echo "🌍 API disponible en: http://$SERVER_IP"


nano install_laravel_api.sh
chmod +x install_laravel_api.sh
./install_laravel_api.sh