FROM dannypas00/frank
ADD .docker/local/xdebug.ini /usr/local/etc/php/conf.d/zz_xdebug.ini

USER root

RUN install-php-extensions ftp

USER app
