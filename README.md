# Present-IA - SaaS B2B de Control de Asistencia

Sistema SaaS multi-tenant escalable para gestión de asistencia de empleados, construido con arquitectura API-first, soporte móvil (PWA) y autenticación dual (email/password y QR dinámico).

## 🏗️ Arquitectura

### Stack Tecnológico
- **Backend**: Laravel 10+
- **Autenticación**: Laravel Sanctum (API tokens)
- **Base de Datos**: MySQL/PostgreSQL
- **Multitenancy**: Single database, shared schema con `company_id` scoping
- **API**: RESTful API versionada (`/api/v1`)
- **Frontend**: PWA mobile-first (preparado para Vue 3/React)

### Principios de Diseño
- **API-First**: Toda la lógica expuesta vía API REST
- **Clean Architecture**: Separación de responsabilidades (Services, Controllers, Policies)
- **Multi-Tenant Seguro**: Scoping automático por `company_id`
- **Escalable**: Preparado para crecimiento horizontal

## 📋 Características Principales

### 1. Autenticación Dual
- **Login Manual**: Email/password o PIN de 6 dígitos
- **Login QR**: Token dinámico con TTL de 30-60 segundos
- **Tokens Sanctum**: Con expiración y refresh

### 2. Sistema de Asistencia
- Check-in/Check-out con validaciones
- Geolocalización opcional (radio configurable)
- Registro de IP, dispositivo y timestamp
- Prevención de doble fichada
- Logs completos de todas las acciones

### 3. QR Dinámico
- Generación de QR con token temporal (TTL 45 segundos)
- Validación server-side
- Consumo automático del token tras uso
- Asociado a `company_id` y ubicación

### 4. Multitenancy
- Aislamiento total de datos por empresa
- Middleware de scoping automático
- Validaciones a nivel de tenant

### 5. Sistema de Suscripciones
- Planes (Basic, Pro, Enterprise)
- Límite de empleados por plan
- Estados: trial, active, suspended, cancelled
- Preparado para integración con Stripe

### 6. Roles y Permisos
- **Empleado**: Fichar y ver historial propio
- **Supervisor**: Ver equipo y aprobar correcciones
- **Admin/RRHH**: Reportes, reglas y configuración completa

### 7. Correcciones de Asistencia
- Solicitud de edición por empleados
- Flujo de aprobación por supervisores
- Historial completo de cambios

## 🗄️ Modelos de Base de Datos

### Principales
- `companies` - Empresas (tenants)
- `users` - Usuarios con roles
- `plans` - Planes de suscripción
- `subscriptions` - Suscripciones activas
- `attendances` - Registros de asistencia
- `attendance_logs` - Logs de todas las acciones
- `attendance_corrections` - Solicitudes de corrección
- `schedules` - Horarios de trabajo
- `devices` - Dispositivos registrados
- `alerts` - Alertas automáticas

## 🚀 Instalación

### Prerrequisitos
- PHP 8.1+
- Composer
- MySQL 5.7+ o PostgreSQL 10+
- Node.js y NPM

### Pasos

1. **Clonar e instalar dependencias**
```bash
composer install
npm install
```

2. **Configurar entorno**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Configurar base de datos en `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=present_ia
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

4. **Ejecutar migraciones**
```bash
php artisan migrate
php artisan db:seed
```

5. **Crear enlace de storage**
```bash
php artisan storage:link
```

6. **Iniciar servidor**
```bash
php artisan serve
```

## 📡 API Endpoints

### Autenticación

#### Login Manual
```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password",
  "device_id": "uuid-device",
  "device_name": "iPhone 12"
}
```

**Respuesta:**
```json
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com",
    "role": "employee",
    "company": {...}
  },
  "token": "1|abc123...",
  "token_type": "Bearer"
}
```

#### Login con PIN
```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "pin": "123456",
  "device_id": "uuid-device"
}
```

#### Login con QR
```http
POST /api/v1/auth/login/qr
Content-Type: application/json

{
  "qr_token": "token-from-qr",
  "company_id": 1,
  "user_id": 1
}
```

#### Obtener Usuario Actual
```http
GET /api/v1/auth/me
Authorization: Bearer {token}
```

#### Logout
```http
POST /api/v1/auth/logout
Authorization: Bearer {token}
```

### QR

#### Generar QR Token (Admin)
```http
POST /api/v1/qr/generate
Authorization: Bearer {token}
```

**Respuesta:**
```json
{
  "qr_data": "{\"token\":\"...\",\"company_id\":1,\"expires_at\":\"...\"}",
  "token": "abc123...",
  "expires_at": "2025-12-18T20:45:00Z",
  "ttl": 45
}
```

#### Obtener QR Actual
```http
GET /api/v1/qr/current
Authorization: Bearer {token}
```

#### Validar QR Token (Público)
```http
POST /api/v1/qr/validate
Content-Type: application/json

{
  "token": "abc123...",
  "company_id": 1
}
```

### Asistencia

#### Check-In
```http
POST /api/v1/attendance/check-in
Authorization: Bearer {token}
Content-Type: application/json

{
  "latitude": -34.603722,
  "longitude": -58.381592,
  "photo": "base64-image-data",
  "device_id": "uuid-device",
  "notes": "Opcional"
}
```

#### Check-Out
```http
POST /api/v1/attendance/check-out
Authorization: Bearer {token}
Content-Type: application/json

{
  "latitude": -34.603722,
  "longitude": -58.381592,
  "photo": "base64-image-data"
}
```

#### Asistencia de Hoy
```http
GET /api/v1/attendance/today
Authorization: Bearer {token}
```

**Respuesta:**
```json
{
  "attendance": {
    "id": 1,
    "date": "2025-12-18",
    "check_in_at": "2025-12-18T09:00:00Z",
    "check_out_at": null,
    "status": "present"
  },
  "can_check_in": false,
  "can_check_out": true
}
```

#### Historial
```http
GET /api/v1/attendance/history?start_date=2025-12-01&end_date=2025-12-31
Authorization: Bearer {token}
```

## 🔐 Seguridad

### Multitenancy
- Todos los queries automáticamente filtrados por `company_id`
- Middleware `EnsureTenantScope` aplicado a todas las rutas API
- Validación de pertenencia a tenant en cada request

### Autenticación
- Tokens Sanctum con expiración configurable
- PINs hasheados (opcional, actualmente en texto plano - mejorar)
- QR tokens con TTL corto (45 segundos)

### Validaciones
- Geolocalización opcional con radio configurable
- Prevención de doble check-in/out
- Validación de horarios según schedule

## 📊 Sistema de Suscripciones

### Planes
- **Basic**: Hasta 10 empleados
- **Pro**: Hasta 50 empleados
- **Enterprise**: Ilimitado

### Estados
- `trial`: Período de prueba (14 días por defecto)
- `active`: Suscripción activa
- `suspended`: Suspendida (pago pendiente)
- `cancelled`: Cancelada

### Límites
- Validación automática de límite de empleados
- Bloqueo de creación de usuarios si se excede el límite

## 🧪 Testing

```bash
# Ejecutar tests
php artisan test

# Tests específicos
php artisan test --filter AttendanceTest
```

## 📝 Estructura del Proyecto

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/V1/          # Controllers API versionados
│   └── Middleware/
│       └── EnsureTenantScope.php
├── Models/                   # Modelos Eloquent
├── Policies/                 # Autorización
└── Services/                 # Lógica de negocio
    ├── AttendanceService.php
    ├── QRService.php
    ├── SubscriptionService.php
    └── AIService.php
database/
├── migrations/               # Migraciones
├── factories/               # Factories
└── seeders/                 # Seeders
routes/
└── api.php                  # Rutas API
```

## 🔄 Próximos Pasos

### Pendientes
- [ ] Sistema de correcciones completo
- [ ] Tests unitarios y de integración
- [ ] Exportación a Excel/CSV
- [ ] Integración con Stripe
- [ ] Webhooks para eventos
- [ ] Notificaciones push
- [ ] Dashboard web (Livewire/React)
- [ ] PWA completo con service worker

### Mejoras Futuras
- [ ] Machine Learning para detección de patrones
- [ ] Biometría (huella dactilar, reconocimiento facial)
- [ ] Integración con sistemas de nómina
- [ ] App móvil nativa (React Native/Flutter)

## 📄 Licencia

MIT License

## 👥 Créditos

Desarrollado con:
- [Laravel](https://laravel.com)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Laravel Livewire](https://livewire.laravel.com)

---

**Present-IA** - Sistema SaaS de Control de Asistencia B2B
