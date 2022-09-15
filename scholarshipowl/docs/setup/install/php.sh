#!/usr/bin/env bash

# TO get last versions of PHP
# sudo add-apt-repository ppa:ondrej/php

apt-get -y install \
    php7.0 \
    php7.0-cli \
    php7.0-mbstring \
    php7.0-gd \
    php7.0-zip \
    php7.0-curl \
    php7.0-imap \
    php7.0-json \
    php7.0-xml \
    php7.0-dom \
    php7.0-ldap \
    php7.0-mcrypt \
    php7.0-soap \
    php7.0-mysql \
    php-memcached

curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer
