#!/bin/sh

set -e
cd /var/www

echo "Starting container..."

if [ ! -f .env ]; then
    cp .env.example .env
fi

if [ ! -d vendor ]; then
    composer install --no-interaction --prefer-dist
fi

mkdir -p \
    bootstrap/cache \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/testing \
    storage/framework/views \
    storage/logs

chmod -R 0777 bootstrap/cache storage

php artisan optimize:clear >/dev/null 2>&1 || true

if ! grep -q "^APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

set -a
. ./.env
set +a

echo "Waiting for DB..."

until MYSQL_PWD="${DB_PASSWORD:-}" mysqladmin ping \
    -h "${DB_HOST:-db}" \
    -u "${DB_USERNAME:-root}" \
    --silent; do
  echo "DB not ready..."
  sleep 2
done

echo "DB is ready"

php artisan migrate --force || true

if [ $# -gt 0 ]; then
    exec "$@"
else
    exec php-fpm -F
fi
