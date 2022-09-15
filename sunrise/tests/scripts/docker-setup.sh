#!/usr/bin/env bash

apt-get -y update
apt-get -i apt-utils

echo 'mysql-server mysql-server/root_password password secret' | debconf-set-selections
echo 'mysql-server mysql-server/root_password_again password secret' | debconf-set-selections

apt-get -y install mysql-server

echo "[client]" >> ~/.my.cnf
echo "password = secret" >> ~/.my.cnf

mysql -e "CREATE USER sunrise@localhost IDENTIFIED BY 'secret';"
mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'sunrise'@'localhost';"
mysql -e "FLUSH PRIVILEGES;"

service mysql restart

apt-get -y install \
    php7.1 \
    php7.1-cli \
    php7.1-mbstring \
    php7.1-gd \
    php7.1-zip \
    php7.1-curl \
    php7.1-imap \
    php7.1-json \
    php7.1-xml \
    php7.1-dom \
    php7.1-ldap \
    php7.1-mcrypt \
    php7.1-soap \
    php7.1-mysql

apt-get -yqq install curl git

curl -sS https://getcomposer.org/installer | php -- --install-dir=/bin --filename=composer
