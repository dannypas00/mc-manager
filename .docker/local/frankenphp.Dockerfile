FROM dannypas00/frank
ADD .docker/local/php.ini /usr/local/etc/php/conf.d/zz_docker_php.ini

USER root

RUN install-php-extensions ftp
RUN apk update && apk add openssh

USER app
