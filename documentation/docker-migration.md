# Docker Migration Guide — EventX

## Overview

This document details every change made to migrate the EventX PHP project from XAMPP to a fully Dockerised development environment. The stack is **PHP 8.2 + Apache**, **MySQL 8.0**, and **phpMyAdmin**, orchestrated with Docker Compose.

---

## Files Created

### 1. `Dockerfile`

**Purpose:** Builds the PHP/Apache image for the `web` service.

**What it does:**
- Starts from the official `php:8.2-apache` base image
- Installs system dependencies (`libpng-dev`, `libjpeg-dev`, `zip`, `unzip`)
- Installs the `mysqli` and `pdo_mysql` PHP extensions (required by the app)
- Enables Apache's `mod_rewrite` module (required by `.htaccess` routing)
- Copies a custom Apache VirtualHost config from `docker/apache.conf`
- Copies all project files into `/var/www/html` inside the container
- Creates upload directories and sets correct `www-data` ownership and permissions

```dockerfile
FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
        libpng-dev libjpeg-dev libwebp-dev zip unzip \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli pdo_mysql
RUN a2enmod rewrite
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
COPY . .

RUN mkdir -p uploads/events uploads/products uploads/profile \
    && chown -R www-data:www-data /var/www/html \
    && find /var/www/html/uploads -type d -exec chmod 755 {} \;
```

---

### 2. `docker/apache.conf`

**Purpose:** Custom Apache VirtualHost that mirrors the XAMPP sub-path setup.

**Why it was needed:**  
The project's `.htaccess` uses `RewriteBase /Eventx/` and `config.php` uses `SITE_URL = http://localhost/Eventx/`. Without an `Alias`, Docker's Apache would serve from `/` and all URL rewrites would break.

**What it does:**
- Sets `DocumentRoot` to `/var/www/html`
- Adds `Alias /Eventx /var/www/html` so the app is accessible at `/Eventx/` just like XAMPP
- Enables `AllowOverride All` so `.htaccess` is respected

```apache
Alias /Eventx /var/www/html

<Directory /var/www/html>
    Options Indexes FollowSymLinks
    AllowOverride All
    Require all granted
</Directory>
```

> **Result:** Zero changes needed to `.htaccess` or any URL in the codebase.

---

### 3. `docker-compose.yml`

**Purpose:** Defines and wires together the three services.

#### Service: `web` (PHP/Apache)
- Builds from the local `Dockerfile`
- Maps host port `8080` → container port `80`
- Passes database credentials and `SITE_URL` as environment variables
- Mounts `./uploads` as a volume so uploaded files persist across container restarts
- Waits for the `db` service to be healthy before starting (via `depends_on` + `condition: service_healthy`)

#### Service: `db` (MySQL 8.0)
- Uses the official `mysql:8.0` image
- Auto-creates the `eventx_db` database and `eventx_user` on first boot via environment variables
- Mounts a **named volume** (`db_data`) at `/var/lib/mysql` so data survives `docker compose down`
- Mounts `./init/` into `/docker-entrypoint-initdb.d/` so all `.sql` files are auto-imported on first boot
- Has a **healthcheck** so dependent services wait for MySQL to be ready
- Exposes port `3307` on the host to avoid conflicts with XAMPP's MySQL on `3306`

#### Service: `phpmyadmin`
- Uses the official `phpmyadmin:latest` image
- Accessible at `http://localhost:8081`
- Pointed at the `db` service

#### Named Volume
```yaml
volumes:
  db_data:
    driver: local
```
The `db_data` volume persists the MySQL data directory. Running `docker compose down` (without `-v`) keeps all data intact.

---

### 4. `.dockerignore`

**Purpose:** Prevents unnecessary files from being copied into the Docker image during `docker build`, keeping the image lightweight.

**Excluded:**
- `.git/`, `.gitignore` — version control metadata
- `Dockerfile`, `docker-compose.yml` — no need inside the image
- `uploads/` — mounted as a runtime volume, not baked in
- `.vscode/`, `.idea/` — IDE config files
- `*.md`, `docs/` — documentation
- `vendor/`, `node_modules/` — dependency caches
- `*.log`, `logs/` — log files

---

### 5. `init/` folder

**Purpose:** Holds SQL and shell scripts that MySQL automatically runs on first boot (when the `db_data` volume is empty). Files run in **alphabetical order**.

| File | Purpose |
|---|---|
| `init/01_schema.sql` | Full database schema (copy of `schema.sql`, with trigger fix — see below) |
| `init/02_seed_data.sql` | Demo data: 10 users across all roles, 4 events, registrations, sponsorships, and supplier products |
| `init/README.md` | Instructions for adding future migration/seed files |

> Scripts only run **once** on first boot. To re-run them: `docker compose down -v && docker compose up -d`

---

### 6. `init/02_seed_data.sql`

**Purpose:** Populates the database with realistic demo data so the app is usable immediately after first boot.

**Inserts:**
- **Users (10):** 1 admin, 2 organizers, 3 participants, 2 sponsors, 2 suppliers
- **Role detail tables:** matching rows in `organizer_details`, `participant_details`, `sponsor_details`, `supplier_details`
- **Events (4):** NYC Tech Summit, AI Hackathon, Art Exhibition, Music Festival — all `published`
- **Registrations (6):** across 3 participants, some with ratings and feedback
- **Sponsorships (4):** at various branding levels (gold, silver, bronze)
- **Supplier products (6):** catering packages and AV gear

> All user passwords are `password123` (bcrypt hashed).

---

## Files Modified

### 7. `config/config.php`

**Purpose:** Make the database connection and site URL work in both Docker and XAMPP without any manual changes when switching environments.

**Before:**
```php
define('SITE_URL', 'http://localhost/Eventx/');
define('DB_HOST', 'localhost');
define('DB_NAME', 'eventx_db');
define('DB_USER', 'root');
define('DB_PASS', '');
```

**After:**
```php
define('DB_HOST', getenv('DB_HOST') !== false ? getenv('DB_HOST') : 'localhost');
define('DB_NAME', getenv('DB_NAME') !== false ? getenv('DB_NAME') : 'eventx_db');
define('DB_USER', getenv('DB_USER') !== false ? getenv('DB_USER') : 'root');
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : '');
define('SITE_URL', getenv('SITE_URL') !== false ? getenv('SITE_URL') : 'http://localhost/Eventx/');
```

**How it works:**

| Environment | `DB_HOST` resolves to | `SITE_URL` resolves to |
|---|---|---|
| Docker | `db` (set by `docker-compose.yml`) | `http://localhost:8080/Eventx/` |
| XAMPP | `localhost` (fallback) | `http://localhost/Eventx/` (fallback) |

> `app/core/DatabaseConnector.php` was **not changed** — it already reads from the `DB_*` constants.

---

### 8. `init/01_schema.sql` — Trigger Fix

**Problem:** The original `schema.sql` omits `DELIMITER` from `BEGIN...END` trigger blocks intentionally (so it can be run through PHP/PDO which doesn't support `DELIMITER`). However, when MySQL CLI imports a `.sql` file directly (which is how `docker-entrypoint-initdb.d` works), it requires `DELIMITER` around multi-statement blocks.

**Error encountered:**
```
ERROR 1064 (42000) at line 603: You have an error in your SQL syntax
```

**Fix applied** (only to `init/01_schema.sql`, not the original `schema.sql`):
```sql
-- Before (breaks MySQL CLI import)
CREATE TRIGGER before_insert_event_supplier_contracts
BEFORE INSERT ON event_supplier_contracts
FOR EACH ROW
BEGIN
    ...
END;

-- After (works in both CLI and Docker)
DELIMITER //
CREATE TRIGGER before_insert_event_supplier_contracts
BEFORE INSERT ON event_supplier_contracts
FOR EACH ROW
BEGIN
    ...
END //
DELIMITER ;
```

> The original `schema.sql` in the project root is **unchanged** and continues to work with XAMPP/PHP/PDO.

---

## Project Structure After Migration

```
Eventx/
├── Dockerfile                  ← NEW: PHP 8.2 + Apache image definition
├── docker-compose.yml          ← NEW: web + db + phpmyadmin services
├── .dockerignore               ← NEW: keeps image lightweight
├── docker/
│   └── apache.conf             ← NEW: VirtualHost with /Eventx alias
├── init/
│   ├── 01_schema.sql           ← NEW: schema with CLI-compatible triggers
│   ├── 02_seed_data.sql        ← NEW: demo data
│   └── README.md               ← NEW: instructions for future migrations
├── schema.sql                  ← UNCHANGED (still used by XAMPP/PHP)
├── config/
│   └── config.php              ← MODIFIED: env var fallbacks added
└── ... (all other files unchanged)
```

---

## Daily Development Workflow

```bash
# Start all services
docker compose up -d

# App   → http://localhost:8080/Eventx/
# PMA   → http://localhost:8081  (user: root / pass: rootpassword)

# Stop all services (data is preserved)
docker compose down
```

## Rebuild the image (after Dockerfile changes)

```bash
docker compose up -d --build
```

## Reset the database (re-runs all init scripts)

```bash
docker compose down -v    # ⚠️ deletes all data
docker compose up -d
```

## Sync database schema with teammates

When you add or change a table:

```bash
# 1. Export current schema
docker compose exec db mysqldump -u root -prootpassword --no-data eventx_db > init/01_schema.sql

# 2. Commit and push
git add init/01_schema.sql
git commit -m "db: describe your schema change"
git push
```

Teammates pull and reset:
```bash
git pull
docker compose down -v
docker compose up -d
```

---

## Credentials Reference

| Service | URL | Username | Password |
|---|---|---|---|
| App | http://localhost:8080/Eventx/ | — | — |
| phpMyAdmin | http://localhost:8081 | `root` | `rootpassword` |
| phpMyAdmin | http://localhost:8081 | `eventx_user` | `eventx_pass` |
| MySQL (host) | `localhost:3307` | `root` | `rootpassword` |
| Seed users | — | see `02_seed_data.sql` | `password123` |
