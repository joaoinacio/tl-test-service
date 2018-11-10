FROM php:7.2-cli

RUN apt-get update && \
    apt-get install -y --no-install-recommends git zip zlib1g-dev

RUN docker-php-ext-install zip

WORKDIR /usr/src/app
RUN curl https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer