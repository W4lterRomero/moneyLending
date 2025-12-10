#!/usr/bin/env bash
set -euo pipefail

# ----------------------------
# One-shot setup script
# - Usa entorno local si existen php/composer/npm
# - Si está corriendo el contenedor "laravel_app" (docker-compose) usa ese entorno
# ----------------------------

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ROOT"

log() { printf "\033[1;34m==>\033[0m %s\n" "$*"; }
warn() { printf "\033[1;33m[warn]\033[0m %s\n" "$*"; }

have() { command -v "$1" >/dev/null 2>&1; }

# Detect docker compose service
php_cmd="php"
composer_cmd="composer"
npm_cmd="npm"

if have docker && (docker compose ps laravel_app >/dev/null 2>&1 || docker-compose ps laravel_app >/dev/null 2>&1); then
  dc="docker compose"
  $dc ps laravel_app >/dev/null 2>&1 || dc="docker-compose"
  if $dc ps laravel_app | grep -q "Up"; then
    log "Usando contenedor laravel_app para PHP/Composer/NPM"
    php_cmd="$dc exec -T app php"
    composer_cmd="$dc exec -T app composer"
    npm_cmd="$dc exec -T app npm"
  else
    warn "laravel_app no está corriendo; usando entorno local"
  fi
fi

log "Verificando .env"
if [ ! -f .env ]; then
  cp .env.example .env
fi

log "Instalando dependencias PHP"
$composer_cmd install --no-interaction --prefer-dist

log "Generando key"
$php_cmd artisan key:generate --force

log "Migrando y sembrando base"
$php_cmd artisan migrate --seed --force

log "Creando storage:link"
$php_cmd artisan storage:link || true

log "Instalando dependencias Front"
$npm_cmd install

log "Compilando assets"
$npm_cmd run build

log "✓ Proyecto listo. Arranca con:"
echo "   $php_cmd artisan serve"
