FROM php:7.2-fpm

ARG BUILD=testing
ARG APP_URL=https://app.scholarship.app

ENV BUILD ${BUILD}
ENV APP_URL ${APP_URL}

LABEL maintainer="PavelZ<pavelz@scholarshipowl.com>" \
      description="This is dockerfile version for Jenkins(Kubernetes), Sunrise project run" \
      version="0.1.0" \
      release-date="2019-03-11"

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

# Node JS
RUN apt-get update && apt-get install -y nodejs

# Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm -rf composer-setup.php && rm -rf /var/lib/apt/lists/*

RUN if [ "${BUILD}" = "testing" ]; then pecl install xdebug-2.6.0 && docker-php-ext-enable xdebug; fi

WORKDIR /var/www/html
ENV FILE_PATH kube/docker/php-fpm

RUN mkdir -p /usr/src/html

# Install app dependencies
COPY composer.json composer.lock /usr/src/html/
RUN cd /usr/src/html && composer install -q --no-scripts --no-autoloader

COPY package*.json .babelrc webpack.admin.common.js webpack.admin.prod.js /usr/src/html/
RUN cd /usr/src/html && npm i

COPY resources /usr/src/html/resources
COPY docs /usr/src/html/docs
COPY public /usr/src/html/public

RUN cd /usr/src/html && npm run prod
RUN cd /usr/src/html && npm run docs:build

# Create folders
RUN cd /usr/src/html && \
    mkdir -p storage/logs && \
  	mkdir -p bootstrap/cache && \
    mkdir -p storage/framework/cache && \
    mkdir -p storage/framework/sessions && \
    mkdir -p storage/framework/views && \
# Create folder for php-fpm slow-log
    mkdir -p /var/log/php-fpm

COPY .env.${BUILD} /usr/src/html/.env

RUN /bin/bash -c "cp -f /usr/src/html/resources/views/layout/admin.blade.php /var/"

COPY ${FILE_PATH}/www-${BUILD}.conf /usr/local/etc/php-fpm.d/www.conf
COPY ${FILE_PATH}/php.ini /usr/local/etc/php/php.ini
COPY ${FILE_PATH}/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

COPY . /usr/src/html

RUN /bin/bash -c "cp -f /var/admin.blade.php /usr/src/html/resources/views/layout/"

CMD ["php-fpm"]
ENTRYPOINT ["/entrypoint.sh"]
