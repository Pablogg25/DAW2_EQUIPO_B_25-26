# 🧺 Sistema de Gestión Integral para Tintorería y Lavandería

**CFGS Desarrollo de Aplicaciones Web — Proyecto Final**  
**Centro:** IES Miguel Herrero
**Equipo:** DAW2 — EQUIPO B  
**Repositorio:** https://github.com/Pablogg25/DAW2_EQUIPO_B_25-26

---

## 📘 Asignaturas involucradas (curso 25–26)

- **DWEC (Cliente):** JavaScript, validaciones, consumo de API, interfaz interactiva
- **DWES (Servidor):** PHP 8.x, arquitectura MVC/REST, seguridad, autenticación
- **Diseño de Interfaces Web:** diseño responsive, accesibilidad, prototipado y experiencia de usuario
- **Despliegue de Aplicaciones Web:** hosting, CI/CD, contenedores, entornos
- **DevOps:** Docker, automatización, integración continua
- **Bases de datos:** modelado, consultas SQL, procedimientos y triggers

---

## 👥 Integrantes del equipo

- Gustavo Rodrigo Bautista Pocohuanca
- Pablo Núñez Sanchez
- Sergio López Iglesias
- Pablo González García

---

## 🎯 Objetivo del proyecto

Desarrollar una **aplicación web completa** para gestionar de forma integral todos los procesos de una tintorería/lavandería:

- Gestión de **clientes**, **usuarios** y **roles**
- Registro y control de **prendas**
- Gestión y seguimiento de **trabajos**
- Control de **inventario** de materiales y productos
- **Facturación automática** por trabajo
- Sistema de **notificaciones** por correo
- Panel de administración seguro, responsivo y accesible
- Despliegue automatizado en entorno de producción

Este proyecto integra todos los módulos del ciclo y aplica buenas prácticas de desarrollo.

---

## 🧩 Funcionalidades principales

### Gestión de usuarios

- Autenticación con hash
- Roles: administrador, empleado, cliente
- Registro, edición y recuperación

### Gestión de clientes

- Alta, baja y modificación
- Búsquedas y filtros
- Historial de prendas y facturas

### Gestión de prendas

- Registrar y actualizar prendas
- Listado por cliente
- Vinculación con trabajos realizados

### Gestión de trabajos

- Crear trabajo (lavado, planchado, arreglo…)
- Cambio de estado
- Asignación de empleado
- Trabajos por fecha
- Aviso de próxima entrega

### Gestión de inventario

- Registrar productos y materiales
- Control de stock y alertas automáticas
- Relación inventario–trabajos

### Facturación

- Generación automática de facturas
- Cálculo de totales e impuestos
- Descarga en PDF
- Historial por cliente

### Notificaciones

- Envío de correos automáticos
- Recordatorios de entrega
- Plantillas configurables

---

## 📦 Módulos y entregables

### 1. Backend — PHP (DWES)

- API REST o MVC
- Controladores por módulo
- Validaciones y middleware de seguridad
- Scripts SQL de creación y datos iniciales

### 2. Frontend — HTML/CSS/JS (DWEC + Interfaces)

- Interfaz responsive basada en prototipos UX
- Formularios accesibles y validaciones
- Peticiones AJAX/Fetch a la API

### 3. Integraciones

- Generación de PDF
- Notificaciones automáticas
- Exportaciones/importaciones si aplica

### 4. DevOps / Despliegue

- Dockerfile y docker-compose
- CI/CD con GitHub Actions
- Entorno de producción reproducible

### 5. Documentación

- Manual de usuario
- Manual técnico
- Diagramas UML: casos de uso, clases, secuencia, despliegue
- Memoria final e IPE

---

## 🛠 Tecnologías utilizadas

- **Frontend:** HTML5, CSS3, JavaScript (ES6), Bootstrap o Tailwind
- **Backend:** PHP 8.x (MVC / REST)
- **Base de datos:** MySQL / MariaDB
- **Servidor:** Apache / Nginx
- **Contenedores:** Docker & docker-compose
- **CI/CD:** GitHub Actions
- **Otros:** FPDF / DomPDF, PHPMailer, Figma

---

## 🤝 Flujo de trabajo (contribución)

1. Crear una rama por funcionalidad:
   - `feature/gestion-clientes`
   - `feature/api-facturacion`
2. Subir commits claros y descriptivos.
3. Abrir Pull Request contra la rama `develop`.
4. Revisiones por parte del equipo.
5. Integración con CI/CD automática.

---

## 🗓 Cronograma del proyecto (plantilla)

- **Fase 1 — Análisis y diseño:** [fechas]
- **Fase 2 — Backend / API:** [fechas]
- **Fase 3 — Frontend / UI:** [fechas]
- **Fase 4 — Integración y despliegue:** [fechas]
- **Entrega final:** [fecha]

---

## 📌 Licencia

Licencia recomendada: **MIT**, **GPL-3.0**, u otra que el equipo decida.

---

## 📬 Contacto

- **Profesor/Tutor:** [Nombre, Email]
- **Portavoz del equipo:** [Nombre, GitHub/Email]

---

© 2025 — Proyecto Final DAW2 — Equipo B
