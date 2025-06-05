[![Ask DeepWiki](https://deepwiki.com/badge.svg)](https://deepwiki.com/sirg98/Kine)

# reflexiokinetp - Sistema de Gestión y Comunicación Clínica

## Descripción

**reflexiokinetp** es una plataforma web para la gestión de pacientes, terapeutas, citas, tratamientos y comunicación interna en clínicas o consultorios. Incluye funcionalidades de administración, mensajería en tiempo real entre usuarios, y un panel de logs para auditoría y monitoreo del sistema.

---

## Características principales

- **Gestión de usuarios:** Pacientes, terapeutas y administradores.
- **Gestión de citas y tratamientos:** Registro, edición y seguimiento.
- **Chat interno:** Comunicación segura entre pacientes y terapeutas, y entre terapeutas.
- **Panel de administración:** Control total sobre usuarios, tratamientos, citas, informes y logs.
- **Panel de logs:** Visualización y filtrado de archivos de logs del sistema, con búsqueda y filtrado por fecha.
- **Interfaz moderna:** Basada en TailwindCSS, responsiva y con soporte para modo oscuro.
- **Seguridad:** Control de sesiones, validación de datos y protección contra inyecciones SQL y XSS.

---

## Estructura del proyecto

```
src/
├── ajax/
│   ├── chat_handler_paciente.php
│   └── chat_handler_teraupeuta.php
├── components/
│   └── db.php
├── pages/
│   ├── admin/
│   │   ├── index.php
│   │   ├── logs/
│   │   │   ├── admin.log
│   │   │   └── purga.log
│   │   └── tabs/
│   │       └── logs.php
│   ├── paciente/
│   │   └── partials/
│   │       └── modal_chat.php
│   └── terapeuta/
│       └── partials/
│           └── modal_chat.php
└── ...
```

---

## Instalación

1. **Requisitos:**
   - PHP 7.4+
   - MySQL/MariaDB
   - Servidor web (Apache recomendado)
   - Composer (opcional, si usas dependencias externas)

2. **Configuración:**
   - Clona el repositorio en tu servidor web.
   - Configura la base de datos en `src/components/db.php`.
   - Asegúrate de que la carpeta `src/pages/admin/logs` tenga permisos de escritura para el sistema de logs.

3. **Base de datos:**
   - Crea las tablas necesarias (`usuarios`, `citas`, `mensajes`, etc.) según el modelo de tu aplicación.

4. **Acceso:**
   - Accede a la plataforma desde tu navegador en la ruta configurada (por ejemplo, `http://localhost/reflexiokinetp/src/pages/admin/index.php`).

---

## Uso

### Panel de Administración

- Navega entre pestañas para gestionar usuarios, tratamientos, citas, informes y logs.
- El panel de logs permite visualizar archivos de logs, buscar texto y filtrar por fecha.

### Chat

- Los pacientes pueden chatear con sus terapeutas desde su panel.
- Los terapeutas pueden chatear con pacientes y otros terapeutas.
- Los mensajes se actualizan automáticamente y se almacenan en la base de datos.

---

## Seguridad

- **Sesiones:** Control de acceso por tipo de usuario.
- **Validación:** Todos los datos de entrada son validados y escapados.
- **SQL seguro:** Uso de prepared statements para evitar inyección SQL.
- **XSS:** Escape de HTML en todos los mensajes y logs.

---

## Personalización

- **Colores y estilos:** Modifica los archivos de TailwindCSS para adaptar la apariencia.
- **Logs:** Puedes agregar más archivos de logs en `src/pages/admin/logs` y se mostrarán automáticamente en el panel.
- **Filtros de logs:** Usa la barra de búsqueda y los selectores de fecha para encontrar información relevante rápidamente.

---

## Créditos

Desarrollado por el equipo de reflexiokinetp.

---

## Licencia

Este proyecto es privado y su uso está restringido al equipo autorizado de la clínica.

---

# Texto adicional proporcionado por el usuario
