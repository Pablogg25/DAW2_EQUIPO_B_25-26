![LogoDeLaEmpresa](Imagenes/Logo%20-%20La%20Cremallera%20-%20Sin%20Fondo.png) ![LogoDeGrupo](Imagenes/Logo%20-%20Grupo%20B%20-%20Sin%20Fondo.png)

# 🧵 La Cremallera — Sistema de Gestión Web para Tienda de Costura

**CFGS Desarrollo de Aplicaciones Web — Proyecto Final**  
**Centro:** IES Miguel Herrero (o el centro correspondiente)  
**Equipo:** DAW2 — EQUIPO B  
**Repositorio:**  
https://github.com/Pablogg25/DAW2_EQUIPO_B_25-26

---

## 👥 Integrantes del equipo

- Gustavo Rodrigo Bautista Pocohuanca
- Pablo Núñez Sánchez
- Sergio López Iglesias
- Pablo González García

---

## 📘 Asignaturas involucradas (Curso 2025–2026)

- **DWEC (Desarrollo Web en Entorno Cliente):** JavaScript, DOM, consumo de API REST
- **DWES (Desarrollo Web en Entorno Servidor):** PHP 8, Laravel, arquitectura MVC/REST, seguridad
- **Diseño de Interfaces Web:** diseño responsive, accesibilidad y maquetación
- **Despliegue de Aplicaciones Web:** entornos, servidores, automatización y hosting
- **DevOps:** Docker, control de versiones, GitHub Actions
- **Bases de Datos:** modelado, SQL y diseño relacional

---

## 🎯 Objetivo del proyecto

Diseñar y desarrollar una **aplicación web profesional** para la gestión integral de la tienda de costura **La Cremallera**, permitiendo centralizar y digitalizar las principales operaciones del negocio:

- Gestión de usuarios y clientes
- Registro y seguimiento de prendas
- Control completo de trabajos de costura
- Gestión de inventario y materiales
- Facturación automática y descarga de documentos
- Sistema de notificaciones por correo electrónico
- Interfaz moderna, accesible y responsive
- Despliegue automatizado y entorno reproducible

---

## 🏗 Arquitectura y diagramas

En este apartado se presentan los **diagramas principales del sistema**, elaborados con **Mermaid**, que permiten comprender la estructura general del proyecto desde distintos niveles: arquitectura, modelo de datos y diseño orientado a objetos.

---

### 🔹 Diagrama de arquitectura general

Este diagrama representa la **arquitectura global del sistema**, mostrando la separación entre frontend, backend y base de datos, así como la interacción entre los distintos componentes del sistema.

[Diagrama de arquitectura — Mermaid]
[![](https://mermaid.ink/img/pako:eNp1UU1vozAU_CvonYiUROErgG9Nc6kUVdXunjbuwWCHWAUbGdPdbpT_vg8XkqZVL9gznpn3wQlKzQUQYKY8SitK2xuxKIRlVFVG961X1lIo20ku_LLWPZ_t7x3j7dibMM9UdcK8ylJ4B6OVFYr7hdF_kJyEz55UH1KmXNbKekiYUu-eHr5EosYf7kMYvrukyXdV1bqSpT-i2X43wG-krLfHq_IO0Sfhe2-jortp8AGnM4rV3s_x9UOjSlt5uAY_DlCWzEqtOlfhJnEqw5llNyW2SHxZAi_8QViwDpO3Gxd3cV5lBStf-tbnsnuZ7TcOfJZOf4j88BYLb0dwbKrwQzYD_kXcIqlyx0WDO5qoUcYLdCF9gzFldLhdUMWLiXjvjCqYQ2UkB2JNL-bQCNOwAcKJKs-jYI-iERQIXrk4sL62FKg6o61l6rfWzeTE5VVHIAdWd4j6FkcUW8kqw5oLa3BOYe51ryyQIExcCJAT_AWShKtlHuVhtMrXeRxH4RzegERhusyyMIjTLF3HaRgl5zn8c2VXyzQJgizL8iCL10G4Ss7_AbmkH80?type=png)](https://mermaid.live/edit#pako:eNp1UU1vozAU_CvonYiUROErgG9Nc6kUVdXunjbuwWCHWAUbGdPdbpT_vg8XkqZVL9gznpn3wQlKzQUQYKY8SitK2xuxKIRlVFVG961X1lIo20ku_LLWPZ_t7x3j7dibMM9UdcK8ylJ4B6OVFYr7hdF_kJyEz55UH1KmXNbKekiYUu-eHr5EosYf7kMYvrukyXdV1bqSpT-i2X43wG-krLfHq_IO0Sfhe2-jortp8AGnM4rV3s_x9UOjSlt5uAY_DlCWzEqtOlfhJnEqw5llNyW2SHxZAi_8QViwDpO3Gxd3cV5lBStf-tbnsnuZ7TcOfJZOf4j88BYLb0dwbKrwQzYD_kXcIqlyx0WDO5qoUcYLdCF9gzFldLhdUMWLiXjvjCqYQ2UkB2JNL-bQCNOwAcKJKs-jYI-iERQIXrk4sL62FKg6o61l6rfWzeTE5VVHIAdWd4j6FkcUW8kqw5oLa3BOYe51ryyQIExcCJAT_AWShKtlHuVhtMrXeRxH4RzegERhusyyMIjTLF3HaRgl5zn8c2VXyzQJgizL8iCL10G4Ss7_AbmkH80)

### 🔹 Diagrama de clases

El diagrama de clases describe el **modelo orientado a objetos** de la aplicación. En él se representan las principales clases del sistema, sus métodos y las relaciones existentes entre ellas.

Clases principales:

- Usuario
- Prenda
- Trabajo
- Factura
- Inventario
- Notificaciones
- Calendario

Este diagrama sirve como base para la implementación del backend en Laravel y la correcta separación de responsabilidades.

[Diagrama de clases — Mermaid]  
[![](https://mermaid.ink/img/pako:eNqtVslu2zAQ_RWBpy6OIcdJnOjQS5bWQFsESXopfBlTY5kpRQoklS6B_72jLdbCWChQHQxrHmd7s1DPjOsYWcS4BGuvBCQG0pVaqYCeUhZ8szkYoYPnSlg874VyQV7Jl3FLbp0RKgmUTtcGh3JMQcih2KHEjVZ6iOQWjYLUYyqj0H5qE38Cux2iRnvcxMIg50KrFhSDw2CDfAt3mAg62A6CGwRTp__mbQvAWLhXEClSofyYXjtU6IUgJ8gJDqYrtiIhY3daNuJdtza3BlUMg9JkpdhXGScyD80xWm5E1uOmBrmW2ngsgZTQEpuKPzBVTB6-PEBNSYXYQ4R2lXs0PBhYw-OwRV0l9xHhT3nfDkslCPNC18pRsuBpb-sgbutspIayGl1TZV_VMfvqfZ1mEslSB-OQrgVhpY8eInkuS4q4v-VqX_ZWm0spqNNw5FQ_gh7flyCLavi2Aj6Ree1vPZdL_V8rcSNUn9br0r-n-zxAnXeFWC_r3SO9mvVYuQHucgNFIgNaNhXW4WWfx6BlnKbxaknXWlNsirZe0u2wgxwmRehg6rh8efmgpp0eihg6SOEFTEKddnXzCgdLVTDl7QzxAv3LleHPrLDHgTZmDHFPbJ3mP74IJVLt205Lh6k33ftCr4M8oRGbYiff7036xvVSK5un-nBzfNWuNFYkgnZAjmrBPnpSVBYecXSho3qiJXGHnK5GcJrIrrdVdyLKU3XxP5ZdEvtO1Cl9JuqGeTXfBSs2W7Hg6OgD_Qun03f0Ul9LUZBpizh6vFnfUWC1pGF3MKrSnrUoKBbfetxPa21FQULbmrTHXfUqt_dWada5HkwLrOaCKG50GsSj1BqgiK7eorNwVGkQYjX5o3odQjIJqrTSRNnmuK0866cnFJf5bwqTTVhiRMwiZ3KcsBQNffDRKyvbfcXcFikbFtHfGDeQS7diK7UjtQzUd63TRtPoPNmyaAPS0lueFauy_jh9kRa8Iw1frhyL5melDRY9s18sOj4Np_Pw_GJ-enZ-HJ6FF_MJ-03icHoyX5wsZqfFbzhb7CbsT-k1nJ4vTnd_AZ36Vpw?type=png)](https://mermaid.live/edit#pako:eNqtVslu2zAQ_RWBpy6OIcdJnOjQS5bWQFsESXopfBlTY5kpRQoklS6B_72jLdbCWChQHQxrHmd7s1DPjOsYWcS4BGuvBCQG0pVaqYCeUhZ8szkYoYPnSlg874VyQV7Jl3FLbp0RKgmUTtcGh3JMQcih2KHEjVZ6iOQWjYLUYyqj0H5qE38Cux2iRnvcxMIg50KrFhSDw2CDfAt3mAg62A6CGwRTp__mbQvAWLhXEClSofyYXjtU6IUgJ8gJDqYrtiIhY3daNuJdtza3BlUMg9JkpdhXGScyD80xWm5E1uOmBrmW2ngsgZTQEpuKPzBVTB6-PEBNSYXYQ4R2lXs0PBhYw-OwRV0l9xHhT3nfDkslCPNC18pRsuBpb-sgbutspIayGl1TZV_VMfvqfZ1mEslSB-OQrgVhpY8eInkuS4q4v-VqX_ZWm0spqNNw5FQ_gh7flyCLavi2Aj6Ree1vPZdL_V8rcSNUn9br0r-n-zxAnXeFWC_r3SO9mvVYuQHucgNFIgNaNhXW4WWfx6BlnKbxaknXWlNsirZe0u2wgxwmRehg6rh8efmgpp0eihg6SOEFTEKddnXzCgdLVTDl7QzxAv3LleHPrLDHgTZmDHFPbJ3mP74IJVLt205Lh6k33ftCr4M8oRGbYiff7036xvVSK5un-nBzfNWuNFYkgnZAjmrBPnpSVBYecXSho3qiJXGHnK5GcJrIrrdVdyLKU3XxP5ZdEvtO1Cl9JuqGeTXfBSs2W7Hg6OgD_Qun03f0Ul9LUZBpizh6vFnfUWC1pGF3MKrSnrUoKBbfetxPa21FQULbmrTHXfUqt_dWada5HkwLrOaCKG50GsSj1BqgiK7eorNwVGkQYjX5o3odQjIJqrTSRNnmuK0866cnFJf5bwqTTVhiRMwiZ3KcsBQNffDRKyvbfcXcFikbFtHfGDeQS7diK7UjtQzUd63TRtPoPNmyaAPS0lueFauy_jh9kRa8Iw1frhyL5melDRY9s18sOj4Np_Pw_GJ-enZ-HJ6FF_MJ-03icHoyX5wsZqfFbzhb7CbsT-k1nJ4vTnd_AZ36Vpw)

---

### 🔹 Diagrama Entidad–Relación (ER)

El diagrama entidad–relación representa el **modelo de datos relacional** de la aplicación, mostrando las tablas principales, sus atributos y las relaciones mediante claves primarias y foráneas.

Este diagrama es la referencia para la implementación de la base de datos en MySQL/MariaDB.

[Diagrama ER — Mermaid]  
[![](https://mermaid.ink/img/pako:eNqtVt9v2kAM_leie6ZVWkpp88YoSGwrVPzYw4SETGLguuQc3V26tZT_fZeMJJALXTstD4jYPvv7PvusbJlPATKPobzjsJYQzcVcOOaZTWad8WA0cV5fz85o6zyMe8O7zsTxnJgUYn3QdNz51Pk8SqMUhdznGuoD-53udDbO0kn0-fJEvuFoOugPup3uYDTs_S242_maQjQmE6g5Csy55NhtlKDI5xBAHlm48pyj4WR2P5osDs4kmof8ZU9sMPzWG06zom8ciSU9YQGnIH8sRn7AxHPhh8kz1mOyw1fg60RCQAsUp5hYUq6NQBJO0D6UMg5B8BX3wRqN7Z_39OFCO4lKQHIaBM7Dl9KjtORi7QiKlhItM0bAQ8uqMcQVCbIciUIpILLzxKDUT5LBYgNqY3kl2TUCbobJ5yRKTwAanRX6G1hIXHMTuEewq05ShXksUQRQIX4sSd-WRPPYZhig8iWPj4HtfT6FJO00EIZQwVk0tAJUS1jCI9UgLSj0P0KhFm0p40CYHUB1np7QRmKw50FpM8eleRUSZOCKPDvrGlU47m_Du7tRgqqW1WS0fR_nJVGIIMwcrgv8VaTFja0HPD3Zm5JRtTllP3NPUfRgM1XqcfGEQn_4qtbyTvP5IDQPIDi2Kk3-j3sueFSVw96QFYA-CZVEdUrYfAtSGqMac45tprItf4SjshIrIATpbOulfN89S6cx7kWMUCh4xDd2QSlTuYQr0DDt34nmaa6TkP7fRe1z8e_EDRfWYGvJA-ZpmWCDRSjNxjevLOM0Z3qDZp0zz_wNcAVJqOdsLnbmWAziO1GUn5SUrDfMW0GozFsSpxD33yxFiFlhKLuUCM28KzdLwbwt-8W8y5Z73nRvbput65tL99q9bTbYszG751fN9lX7opX-uhftXYO9ZEXd85t2a_cbjMW5JA?type=png)](https://mermaid.live/edit#pako:eNqtVt9v2kAM_leie6ZVWkpp88YoSGwrVPzYw4SETGLguuQc3V26tZT_fZeMJJALXTstD4jYPvv7PvusbJlPATKPobzjsJYQzcVcOOaZTWad8WA0cV5fz85o6zyMe8O7zsTxnJgUYn3QdNz51Pk8SqMUhdznGuoD-53udDbO0kn0-fJEvuFoOugPup3uYDTs_S242_maQjQmE6g5Csy55NhtlKDI5xBAHlm48pyj4WR2P5osDs4kmof8ZU9sMPzWG06zom8ciSU9YQGnIH8sRn7AxHPhh8kz1mOyw1fg60RCQAsUp5hYUq6NQBJO0D6UMg5B8BX3wRqN7Z_39OFCO4lKQHIaBM7Dl9KjtORi7QiKlhItM0bAQ8uqMcQVCbIciUIpILLzxKDUT5LBYgNqY3kl2TUCbobJ5yRKTwAanRX6G1hIXHMTuEewq05ShXksUQRQIX4sSd-WRPPYZhig8iWPj4HtfT6FJO00EIZQwVk0tAJUS1jCI9UgLSj0P0KhFm0p40CYHUB1np7QRmKw50FpM8eleRUSZOCKPDvrGlU47m_Du7tRgqqW1WS0fR_nJVGIIMwcrgv8VaTFja0HPD3Zm5JRtTllP3NPUfRgM1XqcfGEQn_4qtbyTvP5IDQPIDi2Kk3-j3sueFSVw96QFYA-CZVEdUrYfAtSGqMac45tprItf4SjshIrIATpbOulfN89S6cx7kWMUCh4xDd2QSlTuYQr0DDt34nmaa6TkP7fRe1z8e_EDRfWYGvJA-ZpmWCDRSjNxjevLOM0Z3qDZp0zz_wNcAVJqOdsLnbmWAziO1GUn5SUrDfMW0GozFsSpxD33yxFiFlhKLuUCM28KzdLwbwt-8W8y5Z73nRvbput65tL99q9bTbYszG751fN9lX7opX-uhftXYO9ZEXd85t2a_cbjMW5JA)

---

## 🧩 Funcionalidades principales

### 👤 Usuarios y clientes

- Inicio y cierre de sesión con autenticación segura
- Cifrado de contraseñas
- Gestión completa de clientes: alta, edición y consulta

### 👗 Prendas

- Registro de prendas asociadas a clientes
- Actualización y consulta de prendas
- Asociación directa con trabajos

### 🧵 Trabajos de costura

- Creación de trabajos a partir de una prenda
- Asignación de empleado responsable
- Estados: pendiente, en proceso, terminado y entregado
- Control de fechas de inicio y entrega
- Envío automático de recordatorios por correo

### 📦 Inventario

- Registro de productos y materiales
- Control de stock y cantidades mínimas
- Alertas automáticas por bajo stock
- Asociación del consumo de materiales a trabajos

### 🧾 Facturación

- Generación automática de facturas
- Cálculo de importes e impuestos
- Descarga de facturas en PDF
- Histórico de facturación por cliente

### 🔔 Notificaciones

- Avisos por cambios de estado del trabajo
- Recordatorios de entrega
- Envío de facturas generadas

---

## 🛠 Tecnologías utilizadas

- **Frontend:** HTML5, CSS3, JavaScript (ES6+), React
- **Backend:** PHP 8, Laravel
- **Base de datos:** MySQL / MariaDB
- **Servidor:** Apache / Nginx
- **Contenedores:** Docker, docker-compose
- **CI/CD:** GitHub Actions
- **Herramientas adicionales:**
  - PHPMailer
  - DomPDF / FPDF
  - Figma

---

## 🤝 Flujo de trabajo y colaboración

1. Crear una rama por funcionalidad (`feature/nombre-funcionalidad`)
2. Realizar commits frecuentes y descriptivos
3. Abrir Pull Request hacia la rama principal
4. Validación automática mediante CI/CD
5. Revisión y fusión del código

---

## 🗓 Cronograma del proyecto

- **Fase 1 — Análisis y diseño:** [fechas]
- **Fase 2 — Backend / API:** [fechas]
- **Fase 3 — Frontend / UI:** [fechas]
- **Fase 4 — Integración, pruebas y despliegue:** [fechas]
- **Entrega final:** [fecha]

---

## 📌 Licencia

Licencia a definir por el equipo (probablemente MIT).

---

## 📬 Contacto

- **Profesor/Tutor:**  
  Alejandro Federico López Camus — aflopezc01@educantabria.es

- **Portavoz del equipo:**  
  Pablo González García — Pgonzalezg12@educantabria.es  
  GitHub: https://github.com/Pablogg25

- **Equipo:**
  Gustavo Rodrigo Bautista Pocuhuanca - gbautistap01@educantabria.es
  GitHub: https://github.com/Hansdreams
  Pablo Nuñez -
  GitHub: 
  Sergio Lopez -
  GitHub: 

---

# Despliegue de API Laravel en EC2 con Nginx, PHP y MySQL

Descripción de los pasos para crear un servidor EC2 en AWS y desplegar la API **LaCremalleraAPI** usando **Nginx, PHP 8.3 y MySQL**.

---

## Creación de la EC2

- Datos de la EC2:

| Propiedad         | Valor                                          |
| ----------------- | ---------------------------------------------- |
| AMI utilizada     | Ubuntu Server 24.04 LTS (HVM), SSD Volume Type |
| Tipo de instancia | t3.micro                                       |
| IP Elástica       | 3.229.92.23                                    |
| Puertos abiertos  | 22, 80, 443                                    |

## Actualización del sistema

```bash
sudo apt update && sudo apt upgrade -y
```

## Instalación de PHP y extensiones necesarias

```bash
sudo apt install php php-cli php-fpm php-mysql php-xml php-mbstring php-curl php-zip unzip curl -y

php -v
```

## Instalación de Composer

```bash
curl -sS https://getcomposer.org/installer -o composer-setup.php

sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer

composer --version
```

## Instalación del MySQL

```bash
sudo apt install mysql-server -y

sudo mysql -u root -p

CREATE DATABASE la_cremallera CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'admin'@'%' IDENTIFIED BY 'admin';
GRANT ALL PRIVILEGES ON la_cremallera.* TO 'admin'@'%';
FLUSH PRIVILEGES;
EXIT;
```

## Clonación del proyecto y permisos

```bash
cd /var/www

sudo git clone https://github.com/Pablogg25/DAW2_EQUIPO_B_25-26.git

cd DAW2_EQUIPO_B_25-26/Backend/LaCremalleraAPI

sudo chown -R www-data:www-data .

```

## Instalación de dependencias y configuración de la API

```bash
sudo apt update && sudo apt upgrade -y

composer install

php artisan migrate

php artisan migrate --seed
```

## Instalación y configuración de Nginx

```bash
sudo apt install nginx -y

sudo nano /etc/nginx/sites-available/lacremallera.com

server {
    listen 80;
    server_name 44.223.237.222;

    root /var/www/DAW2_EQUIPO_B_25-26/Backend/LaCremalleraAPI/public;

    index index.php index.html index.htm;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
    }

    location ~ /\.ht {
        deny all;
    }

    error_log /var/log/nginx/lacremallera_error.log;
    access_log /var/log/nginx/lacremallera_access.log;
}
```

## Activar el sitio y reiniciar Nginx

```bash
sudo ln -s /etc/nginx/sites-available/lacremallera /etc/nginx/sites-enabled/

sudo nginx -t

sudo systemctl restart nginx
```

## Nginx y php8.3-fpm se inician automaticamente cuando arranque el EC2

```bash
sudo systemctl enable nginx
sudo systemctl enable php8.3-fpm

```

© 2025/26 — Proyecto DAW2 — Equipo B — **La Cremallera**
