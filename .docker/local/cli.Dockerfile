FROM dannypas00/php-cli
ADD php.ini /usr/local/etc/php/conf.d/zz_docker_php.ini

USER root

RUN install-php-extensions ftp
RUN apk update && apk add openssh

USER app
