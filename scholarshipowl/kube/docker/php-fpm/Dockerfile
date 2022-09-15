FROM php:7.2.11-fpm-alpine

# NodeJs Yarn Composer Bash
RUN apk update && apk upgrade && apk add --no-cache nodejs yarn composer bash && \
    # Create folders
    mkdir -p /usr/src/html && \
    cd /usr/src/html && \
    mkdir -p storage/logs && \
    mkdir -p bootstrap/cache && \
    mkdir -p storage/framework/cache && \
    mkdir -p storage/framework/sessions && \
    mkdir -p storage/framework/views && \
    # Create folder for php-fpm slow-log and users
    addgroup -S nginx && adduser -S nginx -G nginx && \
    addgroup -S memcached && adduser -S -D -H memcached -G memcached && \
    addgroup -S redis && adduser -S -D -H redis -G redis && \
    mkdir -p /var/log/php-fpm && \
    mkdir -p /var/log/supervisor && \
    touch /var/log/php-fpm/www-slow.log && \
    chown nginx:nginx /var/log/php-fpm/www-slow.log && \
    chmod -R 777 /var/log/php-fpm

RUN apk add --no-cache --virtual .build-dependencies \
    $PHPIZE_DEPS \
    libxml2-dev \
    libcurl \
    libmemcached-dev \
    curl-dev && \
# Dependencies
    apk add --no-cache \
    php7-soap \
    php7-curl \
    php7-json \
    php7-mbstring \
    php7-opcache \
    php7-xml \
    libmemcached \
    memcached \
    redis \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev  \
    supervisor && \
    pecl install -o -f redis memcached  && \
    rm -rf /tmp/pear && \
    docker-php-ext-enable redis memcached && \
    docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && \
    docker-php-ext-install -j$(nproc) gd zip pdo pdo_mysql && \
    apk del .build-dependencies