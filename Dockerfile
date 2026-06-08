# Image produksi Recorda (Laravel + SQLite) untuk Railway / Render / hosting Docker.
# Tidak butuh Node: layout sudah punya fallback ke public/recorda.css & recorda.js.
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

# Install dependency PHP (tanpa dev), siapkan file SQLite kosong & izin tulis.
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database

# Railway/Render mengisi $PORT otomatis. Saat container start:
# 1) buat symlink storage, 2) migrasi + seed database fresh, 3) jalankan server.
CMD php artisan storage:link || true; \
    php artisan migrate --force --seed; \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
