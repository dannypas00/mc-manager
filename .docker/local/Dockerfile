FROM dannypas00/laravel-fpm:8.2

USER 0

# Install xdebug and copy config
RUN docker-php-ext-enable xdebug
ADD xdebug.ini /usr/local/etc/php/conf.d/zz_xdebug.ini

USER 1000
