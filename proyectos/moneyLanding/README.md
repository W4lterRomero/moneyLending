## Money Landing · Sistema de Gestión de Préstamos

Stack: Laravel 12 (PHP 8.2), Fortify, Livewire 3, Vite + Blade + Tailwind, MySQL/PostgreSQL, Chart.js, FullCalendar, Maatwebsite/Excel, DomPDF, Scout (driver database).

### Características
- Autenticación Fortify (login, registro, recordar, verificación y recuperación de contraseña).
- Dashboard con KPIs (prestado, cobrado, préstamos activos, morosidad), filtros de rango y gráficos.
- CRUD completo de Clientes, Préstamos y Pagos con Policies y FormRequests.
- Servicios: amortización y cronograma, agregador de KPIs, filtros avanzados, búsqueda global, export PDF/Excel.
- Vistas tipo Notion: tabla configurable Livewire, calendario FullCalendar, kanban drag & drop, calculadora de préstamos modal.
- Reportes: export a Excel (clientes, préstamos, pagos) y PDF de cartera.
- Configuración de negocio (moneda, tasas, plantillas de contrato), backups JSON y plantilla de importación por comando.
- Notificaciones por vencimientos (job `CheckDueLoansJob`), base para colas/scheduler.

### Requisitos locales
- PHP 8.2+, Composer
- Node 20+ y pnpm o npm
- MySQL 8 o PostgreSQL 14+ (`DB_CONNECTION=mysql|pgsql`)

### Pasos para ejecutarlo (local)
1) Copia el entorno e instala dependencias:
   ```bash
   cp .env.example .env
   composer install
   php artisan key:generate
   ```
2) Configura la base en `.env` (DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD).
3) Migra y carga datos demo:
   ```bash
   php artisan migrate --seed
   ```
4) Instala y levanta el frontend:
   ```bash
   pnpm install   # o npm install
   pnpm run dev   # o npm run dev (Vite en 5173)
   ```
5) Arranca el backend:
   ```bash
   php artisan serve
   ```
   Acceso seed: `admin@example.com` / `password`.

### Pasos con Docker
1) Levanta los contenedores:
   ```bash
   docker-compose up -d --build
   ```
2) Dentro del contenedor app:
   ```bash
   docker exec -it laravel_app bash
   composer install && npm install
   cp .env.example .env && php artisan key:generate
   php artisan migrate --seed
   npm run build   # o npm run dev si prefieres HMR (expuesto en 5173)
   ```
   Puertos: app `8080`, Vite `5173`, phpMyAdmin `8082`.

### Scripts útiles
- `php artisan test` (feature/unit)
- `php artisan queue:listen` (notificaciones)
- `php artisan schedule:run` (lanza `CheckDueLoansJob`)
- `php artisan data:export` (backup JSON)
- `php artisan data:import-template` (plantilla JSON/CSV)

### Roadmap sugerido
- Añadir importación CSV validada y UI para backups.
- Conectar Scout a motor externo (Meili/Algolia) si se requiere fuzziness.
- Sumar pruebas Dusk para flujos críticos (auth, creación de préstamo, pago).
- Automatizar despliegue con Sail/GitHub Actions y publicar assets de Vite.
