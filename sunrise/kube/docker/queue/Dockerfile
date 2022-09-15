FROM phusion/baseimage:0.10.0

RUN apt-get update && apt-get install -y --no-install-recommends supervisor python-software-properties curl && \
    mkdir -p /var/www/logs && \
    mkdir -p /etc/my_init.d && \
    mkdir -p /etc/supervisor/conf.d

RUN add-apt-repository ppa:ondrej/php -y && apt-get update && \
    apt-get install -y --no-install-recommends \
    php-pear php7.2-curl php7.2-dev php7.2-gd php7.2-mbstring php7.2-zip php7.2-mysql php7.2-xml php7.2-soap && \
    curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm -rf composer-setup.php

COPY init.sh /etc/my_init.d/init.sh
COPY laravel-queue.conf /etc/supervisor/conf.d
RUN chmod +x /etc/my_init.d/init.sh

# Clean up APT when done.
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www/html

# Use baseimage-docker's init system.
CMD ["/sbin/my_init"]
