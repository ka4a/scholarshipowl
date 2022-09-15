FROM php:7.2.0-fpm

# see compiled image here htps://gitlab.com/scholarshipowl/scholarshipowl/container_registry
# to compile and push the image:
# cd tests/docker/app
# docker build -t registry.gitlab.com/scholarshipowl/scholarshipowl/sowl_ci_app .
# docker push registry.gitlab.com/scholarshipowl/scholarshipowl/sowl_ci_app:latest
LABEL description="Dockerfile version for GitLab CI tests"

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -q -y --no-install-recommends \
        apt-utils \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libcurl4-openssl-dev \
        libpq-dev \
        libxml2-dev \
        libz-dev \
        libmemcached-dev \
        build-essential \
        wget \
        vim \
        curl \
        git \
        apt-transport-https \
        mysql-server \
        gnupg \
        libc-client-dev \
        libkrb5-dev \
    && rm -r /var/lib/apt/lists/*


RUN pecl install memcached \
    && echo extension=memcached.so >> /usr/local/etc/php/conf.d/memcached.ini \
    && docker-php-ext-enable memcached \
    && docker-php-ext-install -j$(nproc) soap curl json mbstring pdo pdo_mysql opcache xml zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd mysqli \
    && docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap


# Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm -rf composer-setup.php

