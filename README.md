# Plataforma web de monitorización preventiva de vehículos multimotor — Backend API

## Descripción

API REST desarrollada con Laravel para gestionar usuarios, autenticación, vehículos, mantenimientos y reglas preventivas.

Forma parte del Trabajo Final de Máster (TFM):

**Máster Universitario en Desarrollo de Sitios y Aplicaciones Web – UOC**

## Tecnologías utilizadas

- Laravel
- PHP 8.4
- DDEV (Entorno desarrollo)
- PostgreSQL (Producción)
- Sanctum
- Railway

## Funcionalidades

### Autenticación

- Registro
- Login
- Logout
- Tokens mediante Sanctum

### Usuarios

- Gestión de usuarios
- Roles

### Vehículos

- CRUD completo

### Mantenimientos

- CRUD completo
- Historial

### Reglas preventivas

- Gestión de reglas
- Activación/desactivación
- Motor de mantenimiento parametrizable

## Arquitectura

    app/
    ├── Http/
    │ ├── Controllers/
    │ ├── Middleware/
    ├── Models/
    ├── Services/

    database/
    ├── migrations/
    ├── seeders/

    routes/
    ├── api.php

## Variables de entorno principales

APP_ENV=production

APP_URL=https://vehicle-maintenance-api-production.up.railway.app

DB_CONNECTION=pgsql

DB_HOST=...

DB_DATABASE=...

DB_USERNAME=...

DB_PASSWORD=...


## Instalación

Clonar repositorio: 

    git clone https://github.com/raulg84/vehicle-maintenance-api.git

Instalar dependencias: 

    composer install

Generar clave:

    php artisan key:generate

Migrar base de datos:

    php artisan migrate

Ejecutar seeders:

    php artisan db:seed

Ejecutar servidor: 

    php artisan serve

Abrir: 

    http://localhost:8000

## Despliegue
Backend desplegado mediante Railway.

URL pública: 

    https://vehicle-maintenance-api-production.up.railway.app

