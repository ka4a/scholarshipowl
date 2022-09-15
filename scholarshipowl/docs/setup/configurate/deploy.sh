#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
DIR_SSH=/home/deploy/.ssh

useradd -m -G sudo -g www-data deploy

grep -q "deploy ALL=NOPASSWD: ALL" /etc/sudoers
if [[ $? -ne 0 ]]; then
    echo "deploy ALL=NOPASSWD: ALL" >> /etc/sudoers
fi

test -d ${DIR_SSH} || mkdir ${DIR_SSH}

cp ${DIR}/config/deploy.id_rsa ${DIR_SSH}/id_rsa
cp ${DIR}/config/deploy.id_rsa.pub ${DIR_SSH}/id_rsa.pub
touch ${DIR_SSH}/authorized_keys

chown -R deploy:www-data ${DIR_SSH} -R
chmod -R 600 ${DIR_SSH}/*
chmod 700 ${DIR_SSH}

mkdir -p -m 775 /var/www/scholarshipowl
chown www-data:www-data /var/www/scholarshipowl
