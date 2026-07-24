# Dauercamping Verwaltung

Eine Laravel-Web-App zur Verwaltung von Stellplätzen, Pächtern, Verträgen und Zahlungen auf einem Dauercampingplatz.

**Stack:** PHP · Laravel 11 · MySQL · Apache · Tailwind CSS · Laravel Breeze

---

## Voraussetzungen

```bash
# Homebrew installieren (falls noch nicht vorhanden)
/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)"

# PHP, Composer, Node, MySQL installieren
brew install php composer node mysql

# MySQL starten
brew services start mysql

# MySQL root-Passwort setzen (optional)
mysql_secure_installation
```

---

## Installation

```bash
# 1. Dieses Repository klonen
git clone https://github.com/DEIN-USERNAME/dauercamping.git
cd dauercamping

# 2. Setup-Script ausführen
chmod +x setup.sh
./setup.sh
```

Das Script erledigt automatisch:
- Laravel-Projekt erstellen (`laravel/`)
- Breeze + Tailwind installieren
- `.env` konfigurieren
- Datenbank `dauercamping` anlegen
- Alle Migrations ausführen
- Admin-Benutzer anlegen

### Nach dem Setup: `.env` anpassen

```bash
# laravel/.env öffnen und ggf. anpassen:
DB_USERNAME=root
DB_PASSWORD=           # dein MySQL-Passwort eintragen
```

Dann Migrations neu ausführen (falls Passwort geändert):
```bash
cd laravel
php artisan migrate --seed
```

---

## Apache einrichten

### 1. Apache und PHP aktivieren (macOS Homebrew)

```bash
# Falls nicht installiert:
brew install httpd

# mod_rewrite aktivieren – in /opt/homebrew/etc/httpd/httpd.conf:
# LoadModule rewrite_module lib/httpd/modules/mod_rewrite.so  → auskommentieren entfernen
# LoadModule headers_module ...                               → auskommentieren entfernen

# PHP-Modul einbinden (Pfad ggf. anpassen):
# LoadModule php_module /opt/homebrew/opt/php/lib/httpd/modules/libphp.so
# AddType application/x-httpd-php .php
```

### 2. Projektordner verschieben

```bash
# Laravel-Ordner an einen Ort legen, den Apache erreicht:
sudo mkdir -p /Users/Shared/Sites
sudo cp -r laravel /Users/Shared/Sites/dauercamping/

# Berechtigungen setzen
sudo chown -R _www:_www /Users/Shared/Sites/dauercamping/laravel/storage
sudo chmod -R 775 /Users/Shared/Sites/dauercamping/laravel/storage
sudo chmod -R 775 /Users/Shared/Sites/dauercamping/laravel/bootstrap/cache
```

### 3. VirtualHost aktivieren

```bash
# Apache-Konfig kopieren
sudo cp apache/dauercamping.conf /opt/homebrew/etc/httpd/extra/dauercamping.conf

# In /opt/homebrew/etc/httpd/httpd.conf am Ende einfügen:
# Include /opt/homebrew/etc/httpd/extra/dauercamping.conf

# /etc/hosts Eintrag hinzufügen
echo "127.0.0.1  dauercamping.local" | sudo tee -a /etc/hosts

# Apache neu starten
brew services restart httpd
```

### 4. App im Browser öffnen

```
http://dauercamping.local
```

---

## Login

Nach dem Setup ist ein Admin-Benutzer vorhanden:

| Feld | Wert |
|------|------|
| E-Mail | `admin@dauercamping.local` |
| Passwort | `password` |

**Passwort nach dem ersten Login unbedingt ändern!**

---

## GitHub-Setup (neues Repository)

```bash
# 1. Auf github.com ein neues (leeres) Repository anlegen, z.B. "dauercamping"

# 2. Im Projektverzeichnis (dauercamping/):
git init
git add .
git commit -m "Initial commit: Dauercamping Verwaltung"

# 3. Remote verbinden und pushen
git remote add origin https://github.com/DEIN-USERNAME/dauercamping.git
git branch -M main
git push -u origin main
```

### Wichtig: Laravel-Ordner im .gitignore

Das `laravel/`-Verzeichnis wird **nicht** eingecheckt (enthält generierte Dateien, Secrets, Vendor-Pakete). Nur die `stubs/`, `apache/`, `setup.sh` und `README.md` landen im Repo. Nach dem Klonen wird `setup.sh` ausgeführt.

---

## Projektstruktur

```
dauercamping/
├── setup.sh                    # Einmaliges Setup-Script
├── README.md                   # Diese Datei
├── .gitignore
├── apache/
│   └── dauercamping.conf       # Apache VirtualHost-Konfiguration
├── stubs/
│   ├── migrations/             # Datenbank-Migrations
│   ├── models/                 # Eloquent Models
│   ├── controllers/            # HTTP Controller
│   ├── routes/                 # web.php Routen
│   ├── seeders/                # DatabaseSeeder
│   └── views/                  # Blade Templates
│       ├── layouts/app.blade.php
│       ├── components/
│       ├── dashboard/
│       ├── stellplaetze/
│       ├── paechter/
│       ├── vertraege/
│       └── zahlungen/
└── laravel/                    # ← wird von setup.sh generiert (nicht im Git)
```

---

## Datenmodell

```
stellplaetze    paechter
     │              │
     └──────┬───────┘
         vertraege
             │
         zahlungen
```

| Tabelle | Felder |
|---------|--------|
| `stellplaetze` | nummer, bezeichnung, groesse_qm, lage, status |
| `paechter` | vorname, nachname, email, telefon, mobil, adresse, geburtsdatum, status |
| `vertraege` | stellplatz_id, paechter_id, beginn, ende, jahresbetrag, zahlungsrhythmus, status |
| `zahlungen` | vertrag_id, jahr, betrag, faellig_am, bezahlt_am, status, zahlungsart |
| `users` | name, email, password, role (admin/verwalter/leser) |

---

## Entwicklung (lokaler Dev-Server)

```bash
cd laravel

# Laravel Dev-Server starten (Alternative zu Apache)
php artisan serve
# → http://localhost:8000

# Assets kompilieren (Tailwind)
npm run dev      # Watch-Mode
npm run build    # Produktion
```

---

## Erweiterungen

Die App ist modular aufgebaut. Neue Module (z.B. Dokumente, Inventar, Jahresabrechnungen) können als eigene Controller + Views + Migrations hinzugefügt und in `stubs/routes/web.php` eingebunden werden.

Neue Navigation-Einträge kommen in `stubs/views/layouts/app.blade.php` unter `$navItems`.
