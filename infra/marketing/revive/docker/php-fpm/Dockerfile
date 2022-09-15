FROM php:7.0-fpm

MAINTAINER cl8vm1nd <ilyaf@scholarshipowl.com>

WORKDIR /usr/src/

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
        libcurl4-openssl-dev \
        libpq-dev \
        libxml2-dev \
        wget \
        vim \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install -j$(nproc) curl json mbstring pdo pdo_mysql pgsql pdo_pgsql opcache phar xml zip \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd mysqli

COPY ./www.conf /etc/php/7.0/fpm/pool.d/www.conf
COPY ./entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh
RUN mkdir -p /var/www/html && rm -rf /usr/src/*
COPY ./revive /usr/src/revive

CMD ["php-fpm"]
ENTRYPOINT ["/entrypoint.sh"]
