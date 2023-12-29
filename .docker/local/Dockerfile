FROM dannypas00/laravel-fpm:8.2

USER 0

# Install xdebug and copy config
RUN docker-php-ext-enable xdebug
ADD .docker/local/xdebug.ini /usr/local/etc/php/conf.d/zz_xdebug.ini

# Create phpstorm coverage directory
RUN mkdir /opt/phpstorm-coverage
RUN chown 1000:1000 /opt/phpstorm-coverage

USER 1000
