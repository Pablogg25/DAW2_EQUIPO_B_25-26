# Comandos para Ejecutar la API desde el repositorio Local

- Previamente devemos instalar composer en la maquina.
1. composer install
2. composer dump-autoload

- Se hace la primera vez cuando no tienes ni la base de datos y los datos.

- Antes tienes que crear la base de datos con:
CREATE DATABASE la_cremallera CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'admin'@'%' IDENTIFIED BY 'admin';
GRANT ALL PRIVILEGES ON la_cremallera.* TO 'admin'@'%';
FLUSH PRIVILEGES;

- Luego las mirgraciones de tablas y datos
1. php artisan migrate
2. php artisan migrate --seed

- Para finalizar arrancar la API
1. php artisan serve

# ULTIMOS CAMBIOS A LA API
# Comandos para usar la Api con permisos
1. composer install
2. composer dump-autoload
3. php artisan key:generate (se guarda automaticamente en el .env ultima key: APP_KEY=base64:mC78KmHSnQoDVuv/dbxDEvfqR4r8+wtT1X+cEpbrTC0= , verificar si cambia)
4. php artisan migrate
5. php artisan migrate --seed
6. php artisan config:clear
7. php artisan cache:clear
8. php artisan route:clear
9. php artisan optimize:clear
10. php artisan serve