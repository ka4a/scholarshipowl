#!/usr/bin/env bash

apt install php php-mbstring php-mysql php-memcached

apt install nginx memcached redis-server

apt install nodejs npm yarn

# mysql root password: qJbpaTTtC4ySzx27
apt install mysql-server

# ===== Configure deploy user ====

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
DIR_SSH=/home/deploy/.ssh

useradd -m -G sudo -g www-data deploy

grep -q "deploy ALL=NOPASSWD: ALL" /etc/sudoers
if [[ $? -ne 0 ]]; then
    echo "deploy ALL=NOPASSWD: ALL" >> /etc/sudoers
fi

test -d ${DIR_SSH} || mkdir ${DIR_SSH}
touch ${DIR_SSH}/authorized_keys

chown -R deploy:www-data ${DIR_SSH} -R
chmod -R 600 ${DIR_SSH}/*
chmod 700 ${DIR_SSH}

mkdir -p -m 775 /var/www/sunrise
chown www-data:www-data /var/www/sunrise


