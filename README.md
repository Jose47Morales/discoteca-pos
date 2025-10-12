# Discoteca POS ![Version Badge](https://img.shields.io/badge/version-v0.9.0--Beta-blue)

Sistema de punto de venta (POS) diseñado para la administración de una discoteca, con gestión de ventas, cajas, reportes, control de usuarios y roles con permisos granulares.

> **Estado:** Proyecto en fase **Beta v0.9.0** - uso controlado y orientado a pruebas internas.

---

## Cracterísticas principales

- Gestión de ventas con caja asignada.
- Apertura y cierre de caja con historial de movimientos.
- Panel de reportes con exportación PDF y Excel.
- Roles y permisos avanzados usando **Spatie Permissions**.
- Interface adaptable para cajero, vendedor o administrador.
- Dashboard con métricas de negocio en tiempo real.
- Sistema modular fácil de extender.
- Listo para despliegue en producción luego de pruebas de seguridad.

---

## Stack Tecnológico

| Componente | Tecnología |
|------------|------------|
| Backend    | **Laravel 12.x** |
| Frontend   | Blade + TailwindCSS |
| Base de Datos | MySQL / MariaDB |
| Roles & Permisos | Spatie/laravel-permission |
| Exportaciones | DOMPDF y exportación manual a Excel |
| Autenticación | Laravel Breeze con sesiones |
| Seeder de prueba | Generación automática de roles y datos fake |

---

## Instalación en entorno local

### 1. Clonar el repositorio

```bash
git clone https://github.com/Jose57Morales/disocteca-pos.git
cd discoteca-pos
```

### 2. Instalar dependencias

```bash
composer install
npm install && npm run build
```

### 3. Configurar entorno

```bash
cp .env.example .env
php artisan key:generate
```

Edita `.env` y coloca la configuración de base de datos:

```makefile
DB_DATABASE=discoteca_pos
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Migrar y seedear datos

```bash
php artisan migrate --seed
```

Esto generará usuarios de prueba y roles automáticamente.

---

## Accesos de prueba

| Rol | Usuario | Contraseña |
|-----|---------|------------|
| Admin | `admin@example.test` | `password` |
| Cajero | `cajero@example.test` | `password` |
| Vendedor | `vendedor@example.test` | `password` |

> **Importante**: Estos accesos solo se generan si la base de datos tiene menos de 5 usuarios.

---

## Estructura principal del sistema

```rust
/app
    /Http
    /Models
    /Http/Controllers -> Lógica principal
/resources/views      -> Interfaces Blade
/database/seeders     -> Generadores de datos
/routes/web.php       -> Ruteo y endpoints
```
---

## Interfaz del sistema

<img width="1911" height="962" alt="image" src="https://github.com/user-attachments/assets/1ad01237-6dd8-40f3-86ea-8354975c7ba9" />

> Login



<img width="1916" height="964" alt="image" src="https://github.com/user-attachments/assets/a5ca6085-32f8-4169-abbc-73e2b837d1f5" />

> Dashboard principal



<img width="1914" height="963" alt="image" src="https://github.com/user-attachments/assets/35442db0-c3e2-4ea7-8218-e24be334df99" /> <img width="1915" height="964" alt="image" src="https://github.com/user-attachments/assets/78bd9f70-356e-457c-bf1b-ae942f09cd81" />

> Módulo de ventas


<img width="1914" height="964" alt="image" src="https://github.com/user-attachments/assets/9e265642-4a85-4516-bfac-d47dc8dc5b88" />

> Apertura y cierre de cajas



<img width="1913" height="962" alt="image" src="https://github.com/user-attachments/assets/6f0317c9-4040-4d2a-b086-e3d19cbc92a2" />

> Módulo de reportes y descarga



<img width="1914" height="963" alt="image" src="https://github.com/user-attachments/assets/40463580-f46a-4f79-834c-98f25dc9f242" />

> Gestión de roles y permisos

---

## Roles y permisos

El sistema utilixa **Spatie Permission** para asignación granular
- `admin` -> Acceso total.
- `cajero` -> Control de caja, ventas y gastos.
- `vendedor` -> Registro de ventas básicas.
- Permisos agrupados visualmente por módulo en la interfaz.

---

## Licencia

Este proyecto está bajo licencia **MIT**, lo que permite uso comercial, modificación y distribución bajo los términos de la misma.

---

## Aviso legal / Disclaimer

Este software es una **versión Beta.** Está pensado para pruebas internas y validación de flujo de trabajo.
**No se recomienda su uso en entorno productivo real sin revisión previa de seguridad y despliegue.**

---

# Versión en Inglés (English Version)
# Discoteca POS

![Version Badge](https://img.shields.io/badge/version-v0.9.0--Beta-blue)

## Overview
Discoteca POS is a closed-source Point of Sale system designed for nightlife venues, bars, and discotheques. This project is currently in a private development stage and distributed under the MIT License.

## Demo Access Credentials

| Role | User | Password |
|-----|---------|------------|
| Admin | `admin@example.test` | `password` |
| Cajero | `cajero@example.test` | `password` |
| Vendedor | `vendedor@example.test` | `password` |

## Tech Stack
- **Frontend:** Blade
- **Backend:** Laravel (PHP)
- **Database:** MySQL / MariaDB
- **Authentication:** Laravel Breeze / Sanctum
- **Styling:** Bootstrap / Tailwind CSS
- **Deployment Target:** Localhost

## Features
- Role-based authentication (Admin / Cashier)
- Real-time dashboard with sales metrics
- Product & stock management module
- Client order processing optimized for fast workflow in high-traffic venues
- Ticket generation for each sale
- MIT licensed structure for future scalability

## Local Installation Guide

### Requirements
Make sure you have the following installed:
- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL or MariaDB
- Git (optional but recommended)

### Backend Setup (Laravel)
```bash
# Clone the repository
$ git clone https://your-private-repo-url.git
$ cd discoteca-pos/backend

# Install backend dependencies
$ composer install

# Copy environment file
$ cp .env.example .env

# Generate application key
$ php artisan key:generate

# Configure your database credentials in the .env file

# Run migrations
$ php artisan migrate

# Start the backend development server
$ php artisan serve
```

### Frontend Setup
```bash
$ cd discoteca-pos/frontend

# Install frontend dependencies
$ npm install

# Start development server
$ npm run dev
```

## Stock & Product Management
The stock system allows admins to:
- Add new products with pricing and quantity
- Update existing stock in real-time
- Monitor low-stock alerts
- Control availability for cashiers during shifts

## License
This project is licensed under the **MIT License**.

```
MIT License

Permission is hereby granted, free of charge, to any person obtaining a copy...
```

## Notes
- This is a **Closed Demo Build**, not open for public contribution yet.
- Authentication is mandatory. There is no public guest mode due to data sensitivity.

---
*Discoteca POS v0.9.0 Beta – Private Demo Release*



