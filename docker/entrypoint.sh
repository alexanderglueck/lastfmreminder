#!/usr/bin/env bash
# Runtime bootstrap for the php-fpm container: ensure storage dirs, wait for the DB,
# migrate, cache config/routes/views, then hand off to php-fpm. Runs as dockeruser.
# The scheduler container overrides this entrypoint, so only the web app migrates.
set -e
cd /app

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs

# Wait for MariaDB to accept connections before migrating (up to ~30s).
for i in $(seq 1 30); do
  if php artisan db:show >/dev/null 2>&1; then break; fi
  echo "[entrypoint] waiting for database... ($i)"
  sleep 1
done

php artisan migrate --force || echo "[entrypoint] WARNING: migrate failed; continuing"

php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Preserve the base php image's entrypoint, then run the given command (php-fpm).
exec docker-php-entrypoint "$@"
