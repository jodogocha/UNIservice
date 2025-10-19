# UNIservice - Sistema de Gestión de Tickets

Sistema de gestión de tickets de servicio para el Departamento de Informática de las Facultades de la Universidad Nacional de Itapúa.

## 📋 Descripción

UNIservice es un sistema web desarrollado para gestionar eficientemente los pedidos de servicio (mantenimiento, asesoramiento, reparación y configuración de hardware y software) de las diferentes dependencias de las unidades académicas.

El sistema es **configurable para múltiples unidades académicas**, permitiendo que cada facultad pueda tener su propia instancia personalizada con logos, módulos activos y configuraciones propias.

## 🏗️ Tecnologías

- **Backend:** Laravel 11
- **Frontend:** AdminLTE 3, Bootstrap 5, jQuery
- **Base de datos:** MySQL 8.0+
- **Autenticación:** Laravel Breeze + Sistema de Permisos Personalizado
- **Servidor:** XAMPP (desarrollo) / Apache + PHP-FPM (producción)
- **Control de versiones:** Git + GitHub
- **Gráficos:** Chart.js 4.4.0

## ✨ Características Principales

### 🎫 Gestión de Tickets
- ✅ Creación y seguimiento de tickets de servicio
- ✅ Sistema de estados: Pendiente, En Proceso, Listo, Finalizado, Cancelado
- ✅ Niveles de prioridad: Baja, Media, Alta, Urgente
- ✅ Tipos de servicio: Reparación, Mantenimiento, Instalación, Consulta, Otro
- ✅ Asignación automática y manual de técnicos
- ✅ Comentarios y actualizaciones en tiempo real
- ✅ Historial completo de cambios
- ✅ Código único por ticket (TK-YYYY-NNNN)

### 👥 Gestión de Usuarios
- ✅ Sistema de roles y permisos granulares
- ✅ Roles predefinidos: Administrador, Encargado de Laboratorio, Funcionario
- ✅ Gestión de usuarios por dependencias y unidades académicas
- ✅ Perfiles de usuario personalizables
- ✅ Activación/desactivación de usuarios
- ✅ Información del rol en el menú de usuario

### 🏛️ Multi-Unidad Académica
- ✅ Soporte para múltiples facultades/unidades académicas
- ✅ Logos personalizados por unidad académica
- ✅ Configuración dinámica basada en el usuario logueado
- ✅ Gestión de dependencias por unidad académica
- ✅ Aislamiento de datos entre unidades académicas
- ✅ **Sistema de módulos configurables por unidad académica**

### ⚙️ Sistema de Configuración de Módulos (NUEVO)
- ✅ **Activación/desactivación de módulos por unidad académica**
- ✅ **8 módulos configurables:**
  - Gestión de Tickets
  - Gestión de Usuarios
  - Gestión de Dependencias
  - Reportes y Estadísticas
  - Auditoría del Sistema
  - Inventario de Equipos
  - Préstamo de Equipos
  - Uso del Laboratorio
- ✅ **Menú lateral dinámico** según módulos activos
- ✅ **Middleware de validación** de acceso a módulos
- ✅ **Configuración avanzada** por módulo
- ✅ **Interfaz visual** para gestionar módulos

### 🔍 Búsqueda y Filtrado Avanzado
- ✅ Filtros por estado, prioridad, tipo de servicio
- ✅ Búsqueda por código de ticket, asunto o descripción
- ✅ Filtros por dependencia y técnico asignado
- ✅ Paginación con persistencia de filtros
- ✅ Exportación de resultados

### 📊 Dashboard y Reportes
- ✅ Dashboard con estadísticas en tiempo real
- ✅ Gráficos de tickets por estado y prioridad
- ✅ **Centro de reportes** con 9 tipos diferentes:
  - Trabajos por Usuario
  - Solicitudes por Dependencia
  - Ranking de Dependencias
  - Ranking de Usuarios
  - Servicios por Horario
  - Trabajos Asignados
  - Totales Mensuales
  - Totales Anuales
  - Vista general con accesos rápidos
- ✅ Gráficos interactivos con Chart.js
- ✅ Filtros por fecha, mes y año
- ✅ Exportación a PDF
- ✅ Vista personalizada según rol del usuario

### 🔒 Seguridad y Auditoría
- ✅ Sistema completo de auditoría
- ✅ Registro de todas las acciones importantes
- ✅ Filtrado de logs por usuario, acción, módulo y fecha
- ✅ Exportación de logs a CSV
- ✅ Limpieza automática de logs antiguos
- ✅ Autenticación con Laravel Breeze
- ✅ Protección CSRF y validación de formularios
- ✅ Middleware personalizado para permisos y módulos

### 🎨 Interfaz de Usuario
- ✅ Diseño responsive y moderno con AdminLTE 3
- ✅ Interfaz completamente en español
- ✅ Tema oscuro y claro (AdminLTE)
- ✅ Iconos con Font Awesome 6
- ✅ Notificaciones y alertas intuitivas
- ✅ Experiencia de usuario optimizada
- ✅ Menú lateral con iconos de colores
- ✅ Preloader personalizado por unidad académica

## 📦 Instalación

### Requisitos Previos

- PHP >= 8.1 (8.2 recomendado)
- Composer >= 2.0
- MySQL >= 8.0 o MariaDB >= 10.3
- Node.js >= 16.x y npm >= 8.x
- Git
- Extensiones PHP requeridas:
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath
  - Fileinfo
  - GD

### Pasos de Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/jodogocha/UNIservice.git
cd UNIservice