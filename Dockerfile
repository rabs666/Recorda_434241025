# Image produksi Recorda (Laravel + SQLite) untuk Railway.
FROM php:8.2-cli

# Ekstensi PHP yang dibutuhkan Laravel + driver SQLite.
RUN apt-get update && apt-get install -y --no-install-recommends \
        git unzip libonig-dev libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite mbstring bcmath \
    && rm -rf /var/lib/apt/lists/*

# Composer (disalin dari image resmi composer).
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Pakai .env.railway sebagai .env produksi (sudah include APP_KEY yang valid).
RUN cp .env.railway .env \
    && composer install --no-dev --optimize-autoloader --no-interaction --no-progress \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database

# Saat container start: clear cache, symlink storage, migrasi, lalu jalankan server.
CMD php artisan config:clear || true; \
    php artisan cache:clear || true; \
    php artisan storage:link || true; \
    php artisan migrate --force --seed || true; \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
