FROM dannypas00/laravel-fpm:8.2-node
LABEL authors="dannypas00"

COPY --chown=www-data:www-data . /var/www

RUN set -eux; rm -rf bootstrap/cache;

RUN set -eux; composer install \
    --ignore-platform-reqs \
    --classmap-authoritative \
    --optimize-autoloader \
    --prefer-dist \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --no-dev

RUN set -eux; mkdir -pv bootstrap/cache storage/app/public storage/framework/views; \
    php artisan config:cache; \
    php artisan event:cache; \
    php artisan route:cache; \
    php artisan view:cache

RUN set-eux; \
    npm ci; \
    NODE_ENV=production npx vite --mode=production build;
