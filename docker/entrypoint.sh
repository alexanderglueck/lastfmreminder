#!/usr/bin/env bash
# Runtime bootstrap for the php-fpm container: ensure storage dirs, cache
# config/routes/views, then hand off to php-fpm. Runs as dockeruser.
# The app uses no database (sessions and cache are files, the queue is sync),
# so there is no wait for MariaDB and no migrate step.
set -e
cd /app

mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs

php artisan storage:link || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Preserve the base php image's entrypoint, then run the given command (php-fpm).
exec docker-php-entrypoint "$@"
