# Accert - Agent Guide

## Project Overview
PHP 8.1 + Apache web app for collaborative office practice management. Runs in Docker with MariaDB 10.8.2 and phpMyAdmin. Italian PA reuse catalog software (EUPL-1.2).

## Quick Start
```bash
# 1. Rename compose file
cp compose.torename.yaml compose.yaml

# 2. Edit compose.yaml: set MARIADB_ROOT_PASSWORD
# 3. Edit sql/accert.sql: set passwords for 'accert' and 'mariabackup' users
# 4. Create db config
cp wwwroot/db.inc.modello.php wwwroot/db.inc.php
# Edit wwwroot/db.inc.php: set $dbpwd to match sql/accert.sql

# 5. Edit backup.sh: set mariabackup password
# 6. Generate SSL certs
./generaCertificati.sh

# 7. Build and start
docker compose build
docker compose up -d
```

## Key Files
- `compose.yaml` - Docker services (db, phpmyadmin, php)
- `sql/accert.sql` - Schema + initial data + DB users
- `wwwroot/db.inc.php` - PHP DB connection (gitignored)
- `backup.sh` / `restore.sh` - Hot backup/restore via mariabackup
- `generaCertificati.sh` - Self-signed certs for HTTPS (port 7354)

## Database
- Name: `accert`
- Users: `root` (compose.yaml), `accert` (app), `mariabackup` (backup)
- Passwords must match across: compose.yaml, sql/accert.sql, db.inc.php, backup.sh
- Tables use `latin1_general_ci` collation; views use stored functions
- Recursive stored procedures for practice tree creation

## Backup/Restore
```bash
./backup.sh   # Creates tar/bkup.tar.gz (hot backup)
./restore.sh  # Expects tar/bkup.tar.gz, stops DB, restores, restarts
```
Backups go to `tar/`, previous kept as `tar/prev.bkup.tar.gz`

## Ports
- 7354: HTTPS (PHP/Apache)
- 8080: phpMyAdmin
- 3306: MariaDB

## Git Ignored
- `datadir/` - MariaDB data
- `backup/` - Backup staging
- `tar/bkup.tar.gz` - Backup archives
- `certificati/` - SSL certs
- `wwwroot/db.inc.php` - Local DB config
- CA keys and CSR

## Architecture Notes
- No framework: plain PHP with mysqli
- Entry point: `wwwroot/index.php`
- Practices (Accertamento) form a recursive tree via `idAccertamentoPadre`
- Activities (Attività) linked to practices
- Subjects (Soggetto) with roles per practice (Accertatore/Responsabile)
- Documents stored as BLOBs in DB