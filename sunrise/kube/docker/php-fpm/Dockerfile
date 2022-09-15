FROM php:7.2-fpm

LABEL maintainer="cl8vm1nd<ilyaf@scholarshipowl.com>" \
      description="This is dockerfile version for Jenkins(Kubernetes), Sunrise project run" \
      version="0.0.2-beta" \
      release-date="2018-01-01"

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libmcrypt-dev \
        gnupg \
        libcurl4-openssl-dev \
        libpq-dev \
        libxml2-dev \
        libz-dev \
        libmemcached-dev \
        build-essential \
        memcached \
        wget \
        vim \
        curl \
        git \
        apt-transport-https && \
        rm -rf /var/lib/apt/lists/*

RUN pecl install memcached \
    && pecl install xdebug-2.6.0 \
    && pecl install -o -f redis \
    && rm -rf /tmp/pear \
    && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini \
    && echo extension=memcached.so >> /usr/local/etc/php/conf.d/memcached.ini \
    && docker-php-ext-install -j$(nproc) soap curl json mbstring pdo pdo_mysql bcmath opcache xml zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-enable memcached \
    && docker-php-ext-install -j$(nproc) gd mysqli

# NodeJs
RUN curl -sL https://deb.nodesource.com/setup_8.x | bash

# Yarn
RUN apt-get update && apt-get install -y nodejs

# Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm -rf composer-setup.php && rm -rf /var/lib/apt/lists/*
