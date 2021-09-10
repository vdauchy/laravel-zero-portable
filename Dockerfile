FROM php:8.0-cli-alpine

COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

RUN apk add --no-cache \
    bash \
    busybox-suid \
    shadow \
    git \
    openssh-client

RUN ssh-keyscan gitlab.com >> /etc/ssh/ssh_known_hosts \
    ssh-keyscan github.com >> /etc/ssh/ssh_known_hosts

RUN install-php-extensions \
    ast \
    bcmath \
    exif \
    intl \
    opcache \
    pdo_mysql \
    pcntl \
    zip

ENV PHP_APP_ROOT=/usr/src/app

WORKDIR ${PHP_APP_ROOT}
