#!/usr/bin/env bash
# =============================================================================
# Dauercamping App – Setup Script
# Erstellt ein frisches Laravel-Projekt mit Breeze + Tailwind
# und richtet alle Dauercamping-spezifischen Dateien ein.
#
# Voraussetzungen auf dem Mac:
#   brew install php composer node
#   brew services start mysql
# =============================================================================

set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
APP_DIR="$SCRIPT_DIR/laravel"

echo "╔══════════════════════════════════════════════════╗"
echo "║     Dauercamping App – Setup                    ║"
echo "╚══════════════════════════════════════════════════╝"
echo ""

# ── 1. Laravel Projekt erstellen ─────────────────────────────────────────────
echo "▶ 1/7  Laravel Projekt wird erstellt …"
composer create-project laravel/laravel "$APP_DIR" --prefer-dist
cd "$APP_DIR"

# ── 2. Breeze + Tailwind installieren ────────────────────────────────────────
echo "▶ 2/7  Laravel Breeze wird installiert …"
composer require laravel/breeze --dev
php artisan breeze:install blade --dark

# ── 3. NPM-Pakete & Build ────────────────────────────────────────────────────
echo "▶ 3/7  NPM-Pakete werden installiert und kompiliert …"
npm install
npm run build

# ── 4. .env anpassen ─────────────────────────────────────────────────────────
echo "▶ 4/7  .env wird konfiguriert …"
cp .env.example .env
php artisan key:generate

sed -i '' 's/DB_CONNECTION=sqlite/DB_CONNECTION=mysql/' .env
sed -i '' 's/# DB_HOST=127.0.0.1/DB_HOST=127.0.0.1/' .env
sed -i '' 's/# DB_PORT=3306/DB_PORT=3306/' .env
sed -i '' 's/# DB_DATABASE=laravel/DB_DATABASE=dauercamping/' .env
sed -i '' 's/# DB_USERNAME=root/DB_USERNAME=root/' .env
sed -i '' 's/# DB_PASSWORD=/DB_PASSWORD=/' .env

echo ""
echo "  ⚠  MySQL-Zugangsdaten bitte in laravel/.env anpassen:"
echo "     DB_USERNAME und DB_PASSWORD"
echo ""

# ── 5. Datenbank anlegen ─────────────────────────────────────────────────────
echo "▶ 5/7  Datenbank 'dauercamping' wird angelegt …"
mysql -u root -e "CREATE DATABASE IF NOT EXISTS dauercamping CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>/dev/null || \
  echo "  ⚠  Datenbank konnte nicht automatisch angelegt werden. Bitte manuell in MySQL erstellen."

# ── 6. Stubs hineinkopieren ──────────────────────────────────────────────────
echo "▶ 6/7  Anwendungsdateien werden eingespielt …"

# Migrations
cp -f "$SCRIPT_DIR/stubs/migrations/"*.php database/migrations/

# Models
cp -f "$SCRIPT_DIR/stubs/models/"*.php app/Models/

# Controllers
cp -f "$SCRIPT_DIR/stubs/controllers/"*.php app/Http/Controllers/

# Views
cp -rf "$SCRIPT_DIR/stubs/views/." resources/views/

# Routes
cp -f "$SCRIPT_DIR/stubs/routes/web.php" routes/web.php

# Seeder
cp -f "$SCRIPT_DIR/stubs/seeders/DatabaseSeeder.php" database/seeders/DatabaseSeeder.php

# Migrations & Seeder ausführen
php artisan migrate --seed

# ── 7. Fertig ────────────────────────────────────────────────────────────────
echo "▶ 7/7  Setup abgeschlossen!"
echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║  Nächste Schritte:                              ║"
echo "║  1. Apache VirtualHost einrichten (siehe README)║"
echo "║  2. http://dauercamping.local im Browser öffnen ║"
echo "║  3. Login: admin@example.com / password         ║"
echo "╚══════════════════════════════════════════════════╝"
