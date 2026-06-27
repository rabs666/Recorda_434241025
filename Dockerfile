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

# Sediakan .env produksi: copy dari example, patch nilai production,
# generate APP_KEY baru ke dalam .env, lalu install dependency.
RUN cp .env.example .env \
    && sed -i 's/^APP_ENV=.*/APP_ENV=production/' .env \
    && sed -i 's/^APP_DEBUG=.*/APP_DEBUG=false/' .env \
    && sed -i 's|^APP_URL=.*|APP_URL=https://recorda-production-4424.up.railway.app|' .env \
    && sed -i 's/^APP_KEY=.*/APP_KEY=/' .env \
    && composer install --no-dev --optimize-autoloader --no-interaction --no-progress \
    && php artisan key:generate --force \
    && touch database/database.sqlite \
    && chmod -R 777 storage bootstrap/cache database

# Saat container start:
# 1) symlink storage, 2) migrasi + seed database fresh, 3) jalankan server di $PORT dari Railway.
CMD php artisan storage:link || true; \
    php artisan migrate --force --seed || true; \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
