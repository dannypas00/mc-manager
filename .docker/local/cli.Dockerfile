FROM dannypas00/php-cli
ADD xdebug.ini /usr/local/etc/php/conf.d/zz_xdebug.ini

USER root

RUN install-php-extensions ftp

USER app
