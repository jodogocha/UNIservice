# UNIservice - Sistema de Gestión de Tickets

Sistema de gestión de tickets de servicio para el Laboratorio de Informática de la Universidad Nacional de Itapúa.

## 📋 Descripción

UNIservice es un sistema web desarrollado para gestionar eficientemente los pedidos de servicio (mantenimiento, asesoramiento, reparación y configuración de hardware y software) de las diferentes dependencias de la Facultad de Humanidades, Ciencias Sociales y Cultura Guaraní.

El sistema es **configurable para múltiples unidades académicas**, permitiendo que cada facultad pueda tener su propia instancia personalizada con logos y configuraciones propias.

## 🏗️ Tecnologías

- **Backend:** Laravel 11
- **Frontend:** AdminLTE 3, Bootstrap 5, jQuery
- **Base de datos:** MySQL 8.0+
- **Autenticación:** Laravel Breeze + Sistema de Permisos Personalizado
- **Servidor:** XAMPP (desarrollo) / Apache + PHP-FPM (producción)
- **Control de versiones:** Git + GitHub

## ✨ Características Principales

### 🎫 Gestión de Tickets
- ✅ Creación y seguimiento de tickets de servicio
- ✅ Sistema de estados: Pendiente, En Proceso, Listo, Finalizado, Cancelado
- ✅ Niveles de prioridad: Baja, Media, Alta, Urgente
- ✅ Tipos de servicio: Reparación, Mantenimiento, Instalación, Consulta, Otro
- ✅ Asignación automática y manual de técnicos
- ✅ Comentarios y actualizaciones en tiempo real
- ✅ Historial completo de cambios

### 👥 Gestión de Usuarios
- ✅ Sistema de roles y permisos granulares
- ✅ Roles predefinidos: Administrador, Encargado de Laboratorio, Funcionario
- ✅ Gestión de usuarios por dependencias y unidades académicas
- ✅ Perfiles de usuario personalizables
- ✅ Activación/desactivación de usuarios

### 🏛️ Multi-Unidad Académica
- ✅ Soporte para múltiples facultades/unidades académicas
- ✅ Logos personalizados por unidad académica
- ✅ Configuración dinámica basada en el usuario logueado
- ✅ Gestión de dependencias por unidad académica
- ✅ Aislamiento de datos entre unidades académicas

### 🔍 Búsqueda y Filtrado Avanzado
- ✅ Filtros por estado, prioridad, tipo de servicio
- ✅ Búsqueda por código de ticket, asunto o descripción
- ✅ Filtros por dependencia y técnico asignado
- ✅ Paginación con persistencia de filtros
- ✅ Exportación de resultados

### 📊 Dashboard y Reportes
- ✅ Dashboard con estadísticas en tiempo real
- ✅ Gráficos de tickets por estado y prioridad
- ✅ Resumen de actividad reciente
- ✅ Accesos rápidos a funciones principales
- ✅ Vista personalizada según rol del usuario

### 🔒 Seguridad y Auditoría
- ✅ Sistema completo de auditoría
- ✅ Registro de todas las acciones importantes
- ✅ Filtrado de logs por usuario, acción, módulo y fecha
- ✅ Exportación de logs a CSV
- ✅ Autenticación con Laravel Breeze
- ✅ Protección CSRF y validación de formularios

### 🎨 Interfaz de Usuario
- ✅ Diseño responsive y moderno con AdminLTE 3
- ✅ Interfaz completamente en español
- ✅ Tema oscuro y claro (AdminLTE)
- ✅ Iconos con Font Awesome
- ✅ Notificaciones y alertas intuitivas
- ✅ Experiencia de usuario optimizada

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
```

2. **Instalar dependencias de PHP**
```bash
composer install
```

3. **Instalar dependencias de Node.js**
```bash
npm install
```

4. **Configurar el archivo de entorno**
```bash
cp .env.example .env
```

5. **Editar el archivo `.env` con tus configuraciones**
```env
APP_NAME=UNIservice
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uniservice
DB_USERNAME=root
DB_PASSWORD=

# Configuración de la Facultad
FACULTAD_CODIGO=FHCSyCG
```

6. **Generar la clave de la aplicación**
```bash
php artisan key:generate
```

7. **Crear la base de datos**
```sql
CREATE DATABASE uniservice CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

8. **Ejecutar las migraciones**
```bash
php artisan migrate
```

9. **Ejecutar los seeders (datos de prueba)**
```bash
php artisan db:seed
```

10. **Crear el enlace simbólico para el storage**
```bash
php artisan storage:link
```

11. **Compilar los assets**
```bash
npm run dev
```

12. **Iniciar el servidor de desarrollo**
```bash
php artisan serve
```

Ahora puedes acceder al sistema en: `http://localhost:8000`

## 👤 Usuarios de Prueba

Después de ejecutar los seeders, puedes usar estos usuarios:

| Email | Contraseña | Rol | Permisos |
|-------|-----------|-----|----------|
| jgomez@uni.edu.py | password | Administrador | Acceso completo al sistema |
| tecnico@uni.edu.py | password | Encargado de Lab | Gestión de tickets de su unidad |
| mledesma@uni.edu.py | password | Funcionario | Crear y ver sus propios tickets |

## 🔧 Configuración Adicional

### Configurar Logos por Unidad Académica

1. Coloca los logos en `public/images/logos/`
2. Edita el archivo `database/seeders/UnidadAcademicaSeeder.php`
3. Ejecuta:
```bash
php artisan db:seed --class=UnidadAcademicaSeeder
```

### Configurar Correo Electrónico

Edita el `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=tu-email@gmail.com
MAIL_PASSWORD=tu-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@uni.edu.py
MAIL_FROM_NAME="${APP_NAME}"
```

### Personalizar Permisos

Edita `database/seeders/PermissionSeeder.php` y ejecuta:
```bash
php artisan db:seed --class=PermissionSeeder
```

## 📁 Estructura del Proyecto

```
UNIservice/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Controladores principales
│   │   └── Middleware/        # Middlewares personalizados
│   ├── Models/               # Modelos Eloquent
│   └── Providers/            # Service Providers
├── config/
│   ├── adminlte.php          # Configuración de AdminLTE
│   └── facultad.php          # Configuración de unidades académicas
├── database/
│   ├── migrations/           # Migraciones de BD
│   └── seeders/             # Seeders de datos iniciales
├── public/
│   ├── css/                 # Estilos personalizados
│   └── images/logos/        # Logos de unidades académicas
├── resources/
│   └── views/               # Vistas Blade
└── routes/
    └── web.php              # Rutas de la aplicación
```

## 🚀 Despliegue en Producción

### 1. Preparar el servidor

```bash
# Actualizar paquetes
sudo apt update && sudo apt upgrade -y

# Instalar Apache, PHP y MySQL
sudo apt install apache2 php8.2 php8.2-fpm mysql-server -y
sudo apt install php8.2-mysql php8.2-xml php8.2-mbstring php8.2-gd php8.2-curl -y

# Instalar Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 2. Configurar la aplicación

```bash
# Clonar el repositorio
cd /var/www/
sudo git clone https://github.com/jodogocha/UNIservice.git

# Permisos
sudo chown -R www-data:www-data /var/www/UNIservice
sudo chmod -R 755 /var/www/UNIservice

# Instalar dependencias
cd /var/www/UNIservice
composer install --optimize-autoloader --no-dev

# Configurar .env
cp .env.example .env
nano .env  # Editar configuraciones de producción

# Preparar la aplicación
php artisan key:generate
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Configurar Apache

Crear archivo `/etc/apache2/sites-available/uniservice.conf`:

```apache
<VirtualHost *:80>
    ServerName uniservice.uni.edu.py
    ServerAdmin admin@uni.edu.py
    DocumentRoot /var/www/UNIservice/public

    <Directory /var/www/UNIservice/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/uniservice-error.log
    CustomLog ${APACHE_LOG_DIR}/uniservice-access.log combined
</VirtualHost>
```

```bash
# Activar el sitio
sudo a2ensite uniservice.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## 🔄 Actualizaciones

```bash
# Obtener últimos cambios
git pull origin main

# Actualizar dependencias
composer install --optimize-autoloader --no-dev

# Ejecutar migraciones pendientes
php artisan migrate --force

# Limpiar y recrear cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🧪 Testing

```bash
# Ejecutar todos los tests
php artisan test

# Ejecutar tests específicos
php artisan test --filter=TicketTest
```

## 📝 Desarrollo

### Crear un nuevo módulo

```bash
# Crear modelo con migración
php artisan make:model NombreModelo -m

# Crear controlador
php artisan make:controller NombreController --resource

# Crear request de validación
php artisan make:request NombreRequest
```

### Convenciones de código

- PSR-12 para PHP
- Nombres en español para vistas y textos de usuario
- Nombres en inglés para código (variables, funciones, clases)
- Comentarios en español

## 🐛 Resolución de Problemas

### Error: "Class 'permission' does not exist"

```bash
php artisan config:clear
php artisan cache:clear
```

### Filtros de búsqueda no se mantienen al paginar

✅ **Solucionado en la última versión**. Los controladores ahora usan `->appends(request()->query())`.

### El logo no cambia según el usuario

Verifica que:
1. El middleware `ConfigureAdminLteLogo` esté registrado en `bootstrap/app.php`
2. El usuario tenga una unidad académica asignada
3. La unidad académica tenga un logo configurado

```bash
php artisan tinker
>>> $user = \App\Models\User::find(1);
>>> $user->unidadAcademica->logo;
```

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/NuevaCaracteristica`)
3. Commit tus cambios (`git commit -m 'Agregar nueva característica'`)
4. Push a la rama (`git push origin feature/NuevaCaracteristica`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto es de código abierto y está disponible bajo la [Licencia MIT](LICENSE).

## 👨‍💻 Autor

**José Daniel Gómez**
- GitHub: [@jodogocha](https://github.com/jodogocha)
- Email: jgomez@uni.edu.py

## 🙏 Agradecimientos

- Universidad Nacional de Itapúa
- Facultad de Humanidades, Ciencias Sociales y Cultura Guaraní
- Departamento de TICs

## 📞 Soporte

Para soporte técnico o reportar bugs:
- Crear un [Issue en GitHub](https://github.com/jodogocha/UNIservice/issues)
- Enviar email a: jgomez@uni.edu.py

---

⭐ Si este proyecto te fue útil, considera darle una estrella en GitHub.

**Versión:** 1.0.0  
**Última actualización:** Octubre 2025