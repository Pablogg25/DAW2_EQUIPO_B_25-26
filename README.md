# 🧵 La Cremallera — Sistema de Gestión Web para Tienda de Costura

**CFGS Desarrollo de Aplicaciones Web — Proyecto Final**  
**Centro:** IES Miguel Herrero (o el centro correspondiente)  
**Equipo:** DAW2 — EQUIPO B  
**Repositorio:** [https://github.com/Pablogg25/DAW2_EQUIPO_B_25-26](https://github.com/Pablogg25/DAW2_EQUIPO_B_25-26)

---

## 👥 Integrantes del equipo

- Gustavo Rodrigo Bautista Pocohuanca
- Pablo Núñez Sanchez
- Sergio López Iglesias
- Pablo González García

---

## 📘 Asignaturas involucradas (curso 25–26)

- **DWEC (Cliente):** JavaScript, DOM, consumo de API
- **DWES (Servidor):** PHP 8.x, arquitectura MVC/REST, seguridad
- **Diseño de Interfaces Web:** diseño responsive, accesibilidad, maquetación
- **Despliegue de Aplicaciones Web:** entornos, hosting, CI/CD
- **DevOps:** Docker, automatización, GitHub Actions
- **Bases de Datos:** modelado, SQL, procedimientos

---

## 🎯 Objetivo del proyecto

Diseñar y desarrollar una **aplicación web profesional** para la gestión integral de la tienda de costura **La Cremallera**, permitiendo centralizar todas las operaciones del negocio:

- Gestión de clientes y usuarios
- Registro de prendas y seguimiento
- Gestión de trabajos de costura de principio a fin
- Control de inventario y materiales
- Facturación automática y descarga de documentos
- Sistema de notificaciones (correo)
- Interfaz moderna y accesible
- Despliegue automatizado y entorno reproducible

---

## **Arquitectura y diagramas**

A continuación se incluyen dos diagramas Mermaid que muestran la arquitectura general del sistema y el flujo de trabajo principal. Cada diagrama tiene un breve contexto para facilitar su lectura.

### **Diagrama: Arquitectura y componentes**

Visión general de los componentes (Frontend, Backend, Base de datos, servicios de notificación) y cómo se comunican.

[![](https://mermaid.ink/img/pako:eNqtVslu2zAQ_RWBpy6OIcdJnOjQS5bWQFsESXopfBlTY5kpRQoklS6B_72jLdbCWChQHQxrHmd7s1DPjOsYWcS4BGuvBCQG0pVaqYCeUhZ8szkYoYPnSlg874VyQV7Jl3FLbp0RKgmUTtcGh3JMQcih2KHEjVZ6iOQWjYLUYyqj0H5qE38Cux2iRnvcxMIg50KrFhSDw2CDfAt3mAg62A6CGwRTp__mbQvAWLhXEClSofyYXjtU6IUgJ8gJDqYrtiIhY3daNuJdtza3BlUMg9JkpdhXGScyD80xWm5E1uOmBrmW2ngsgZTQEpuKPzBVTB6-PEBNSYXYQ4R2lXs0PBhYw-OwRV0l9xHhT3nfDkslCPNC18pRsuBpb-sgbutspIayGl1TZV_VMfvqfZ1mEslSB-OQrgVhpY8eInkuS4q4v-VqX_ZWm0spqNNw5FQ_gh7flyCLavi2Aj6Ree1vPZdL_V8rcSNUn9br0r-n-zxAnXeFWC_r3SO9mvVYuQHucgNFIgNaNhXW4WWfx6BlnKbxaknXWlNsirZe0u2wgxwmRehg6rh8efmgpp0eihg6SOEFTEKddnXzCgdLVTDl7QzxAv3LleHPrLDHgTZmDHFPbJ3mP74IJVLt205Lh6k33ftCr4M8oRGbYiff7036xvVSK5un-nBzfNWuNFYkgnZAjmrBPnpSVBYecXSho3qiJXGHnK5GcJrIrrdVdyLKU3XxP5ZdEvtO1Cl9JuqGeTXfBSs2W7Hg6OgD_Qun03f0Ul9LUZBpizh6vFnfUWC1pGF3MKrSnrUoKBbfetxPa21FQULbmrTHXfUqt_dWada5HkwLrOaCKG50GsSj1BqgiK7eorNwVGkQYjX5o3odQjIJqrTSRNnmuK0866cnFJf5bwqTTVhiRMwiZ3KcsBQNffDRKyvbfcXcFikbFtHfGDeQS7diK7UjtQzUd63TRtPoPNmyaAPS0lueFauy_jh9kRa8Iw1frhyL5melDRY9s18sOj4Np_Pw_GJ-enZ-HJ6FF_MJ-03icHoyX5wsZqfFbzhb7CbsT-k1nJ4vTnd_AZ36Vpw?type=png)](https://mermaid.live/edit#pako:eNqtVslu2zAQ_RWBpy6OIcdJnOjQS5bWQFsESXopfBlTY5kpRQoklS6B_72jLdbCWChQHQxrHmd7s1DPjOsYWcS4BGuvBCQG0pVaqYCeUhZ8szkYoYPnSlg874VyQV7Jl3FLbp0RKgmUTtcGh3JMQcih2KHEjVZ6iOQWjYLUYyqj0H5qE38Cux2iRnvcxMIg50KrFhSDw2CDfAt3mAg62A6CGwRTp__mbQvAWLhXEClSofyYXjtU6IUgJ8gJDqYrtiIhY3daNuJdtza3BlUMg9JkpdhXGScyD80xWm5E1uOmBrmW2ngsgZTQEpuKPzBVTB6-PEBNSYXYQ4R2lXs0PBhYw-OwRV0l9xHhT3nfDkslCPNC18pRsuBpb-sgbutspIayGl1TZV_VMfvqfZ1mEslSB-OQrgVhpY8eInkuS4q4v-VqX_ZWm0spqNNw5FQ_gh7flyCLavi2Aj6Ree1vPZdL_V8rcSNUn9br0r-n-zxAnXeFWC_r3SO9mvVYuQHucgNFIgNaNhXW4WWfx6BlnKbxaknXWlNsirZe0u2wgxwmRehg6rh8efmgpp0eihg6SOEFTEKddnXzCgdLVTDl7QzxAv3LleHPrLDHgTZmDHFPbJ3mP74IJVLt205Lh6k33ftCr4M8oRGbYiff7036xvVSK5un-nBzfNWuNFYkgnZAjmrBPnpSVBYecXSho3qiJXGHnK5GcJrIrrdVdyLKU3XxP5ZdEvtO1Cl9JuqGeTXfBSs2W7Hg6OgD_Qun03f0Ul9LUZBpizh6vFnfUWC1pGF3MKrSnrUoKBbfetxPa21FQULbmrTHXfUqt_dWada5HkwLrOaCKG50GsSj1BqgiK7eorNwVGkQYjX5o3odQjIJqrTSRNnmuK0866cnFJf5bwqTTVhiRMwiZ3KcsBQNffDRKyvbfcXcFikbFtHfGDeQS7diK7UjtQzUd63TRtPoPNmyaAPS0lueFauy_jh9kRa8Iw1frhyL5melDRY9s18sOj4Np_Pw_GJ-enZ-HJ6FF_MJ-03icHoyX5wsZqfFbzhb7CbsT-k1nJ4vTnd_AZ36Vpw)

### **Diagrama: Flujo de trabajo**

Secuencia típica desde el cliente hasta la entrega y facturación (creación de trabajo, asignación, proceso, factura y notificaciones).

[![](https://mermaid.ink/img/pako:eNqtVt9v2kAM_leie6ZVWkpp88YoSGwrVPzYw4SETGLguuQc3V26tZT_fZeMJJALXTstD4jYPvv7PvusbJlPATKPobzjsJYQzcVcOOaZTWad8WA0cV5fz85o6zyMe8O7zsTxnJgUYn3QdNz51Pk8SqMUhdznGuoD-53udDbO0kn0-fJEvuFoOugPup3uYDTs_S242_maQjQmE6g5Csy55NhtlKDI5xBAHlm48pyj4WR2P5osDs4kmof8ZU9sMPzWG06zom8ciSU9YQGnIH8sRn7AxHPhh8kz1mOyw1fg60RCQAsUp5hYUq6NQBJO0D6UMg5B8BX3wRqN7Z_39OFCO4lKQHIaBM7Dl9KjtORi7QiKlhItM0bAQ8uqMcQVCbIciUIpILLzxKDUT5LBYgNqY3kl2TUCbobJ5yRKTwAanRX6G1hIXHMTuEewq05ShXksUQRQIX4sSd-WRPPYZhig8iWPj4HtfT6FJO00EIZQwVk0tAJUS1jCI9UgLSj0P0KhFm0p40CYHUB1np7QRmKw50FpM8eleRUSZOCKPDvrGlU47m_Du7tRgqqW1WS0fR_nJVGIIMwcrgv8VaTFja0HPD3Zm5JRtTllP3NPUfRgM1XqcfGEQn_4qtbyTvP5IDQPIDi2Kk3-j3sueFSVw96QFYA-CZVEdUrYfAtSGqMac45tprItf4SjshIrIATpbOulfN89S6cx7kWMUCh4xDd2QSlTuYQr0DDt34nmaa6TkP7fRe1z8e_EDRfWYGvJA-ZpmWCDRSjNxjevLOM0Z3qDZp0zz_wNcAVJqOdsLnbmWAziO1GUn5SUrDfMW0GozFsSpxD33yxFiFlhKLuUCM28KzdLwbwt-8W8y5Z73nRvbput65tL99q9bTbYszG751fN9lX7opX-uhftXYO9ZEXd85t2a_cbjMW5JA?type=png)](https://mermaid.live/edit#pako:eNqtVt9v2kAM_leie6ZVWkpp88YoSGwrVPzYw4SETGLguuQc3V26tZT_fZeMJJALXTstD4jYPvv7PvusbJlPATKPobzjsJYQzcVcOOaZTWad8WA0cV5fz85o6zyMe8O7zsTxnJgUYn3QdNz51Pk8SqMUhdznGuoD-53udDbO0kn0-fJEvuFoOugPup3uYDTs_S242_maQjQmE6g5Csy55NhtlKDI5xBAHlm48pyj4WR2P5osDs4kmof8ZU9sMPzWG06zom8ciSU9YQGnIH8sRn7AxHPhh8kz1mOyw1fg60RCQAsUp5hYUq6NQBJO0D6UMg5B8BX3wRqN7Z_39OFCO4lKQHIaBM7Dl9KjtORi7QiKlhItM0bAQ8uqMcQVCbIciUIpILLzxKDUT5LBYgNqY3kl2TUCbobJ5yRKTwAanRX6G1hIXHMTuEewq05ShXksUQRQIX4sSd-WRPPYZhig8iWPj4HtfT6FJO00EIZQwVk0tAJUS1jCI9UgLSj0P0KhFm0p40CYHUB1np7QRmKw50FpM8eleRUSZOCKPDvrGlU47m_Du7tRgqqW1WS0fR_nJVGIIMwcrgv8VaTFja0HPD3Zm5JRtTllP3NPUfRgM1XqcfGEQn_4qtbyTvP5IDQPIDi2Kk3-j3sueFSVw96QFYA-CZVEdUrYfAtSGqMac45tprItf4SjshIrIATpbOulfN89S6cx7kWMUCh4xDd2QSlTuYQr0DDt34nmaa6TkP7fRe1z8e_EDRfWYGvJA-ZpmWCDRSjNxjevLOM0Z3qDZp0zz_wNcAVJqOdsLnbmWAziO1GUn5SUrDfMW0GozFsSpxD33yxFiFlhKLuUCM28KzdLwbwt-8W8y5Z73nRvbput65tL99q9bTbYszG751fN9lX7opX-uhftXYO9ZEXd85t2a_cbjMW5JA)

---

## 🧩 Funcionalidades principales

### Usuarios y clientes

- Inicio/cierre de sesión con seguridad y cifrado
- Control de roles (empleado/administrador)
- Gestión completa del cliente: registro, edición y seguimiento

### Prendas

- Registro de prendas y tipos
- Actualización y listado por cliente
- Asociación automática con trabajos

### Trabajos de costura

- Creación de un trabajo a partir de una prenda
- Asignación de empleado responsable
- Estados del trabajo: pendiente, en proceso, terminado, entregado
- Control de fechas: inicio y entrega
- Recordatorios automáticos por email

### Inventario

- Alta de productos y materiales
- Control de stock y cantidades mínimas
- Alertas automáticas cuando el inventario baja de umbral
- Consumo de materiales asociado a un trabajo

### Facturación

- Generación automática de facturas por trabajo o conjunto de trabajos
- Cálculo de importes, IVA, total
- Descarga de factura en PDF
- Historial por cliente

### Notificaciones

- Envío de correos por estado del trabajo
- Recordatorios cercanos a la fecha de entrega

---

## 🛠 Tecnologías utilizadas

- **Frontend:** HTML5, CSS3, JavaScript (ES6+), React
- **Backend:** PHP 8 / Laravel
- **BBDD:** MySQL / MariaDB
- **Servidor:** Apache / Nginx
- **Contenedores:** Docker, docker-compose
- **CI/CD:** GitHub Actions
- **Librerías adicionales:**
  - PHPMailer (emails)
  - DomPDF / FPDF (PDFs)
  - Figma (diseño de UI)

---

## 🤝 Flujo de trabajo (contribución)

1. Crear rama por funcionalidad (`feature/nombre-funcionalidad`).
2. Hacer commits descriptivos y frecuentes.
3. Abrir Pull Request hacia `main`.
4. Validación automática mediante CI/CD.
5. Revisión y merge.

---

## 🗓 Cronograma del proyecto (plantilla)

- **Fase 1 — Análisis y diseño:** [fechas]
- **Fase 2 — Backend / API:** [fechas]
- **Fase 3 — Frontend / UI:** [fechas]
- **Fase 4 — Integración, pruebas y despliegue:** [fechas]
- **Entrega final:** [fecha]

---

## 📌 Licencia

Licencia a definir por el equipo (MIT probablemente).

---

## 📬 Contacto

- **Profesor/Tutor:** [Alejandro Federico López Camus, aflopezc01@educantabria.es]
- **Portavoz:** [Pablo González García, Pgonzalezg12@educantabria.es/[GitHub](https://github.com/Pablogg25)]

---

© 2025 — Proyecto DAW2 — Equipo B — La Cremallera
