#!/usr/bin/env bash

if [ "$#" -ne 1 ]; then
    echo "Usage: $0 hostname"
    exit 1;
fi

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Install requirements

apt-get -y update

${DIR}/install/php.sh
${DIR}/install/node.sh
${DIR}/install/nginx.sh
${DIR}/install/mysql-server.sh
${DIR}/install/redis.sh
${DIR}/install/memcached.sh
${DIR}/install/beanstalkd.sh

# Configurate server

${DIR}/configurate/php.sh
${DIR}/configurate/mysql.sh
${DIR}/configurate/nginx.sh $1 /var/www/scholarshipowl/current/public
${DIR}/configurate/deploy.sh
${DIR}/configurate/redis.sh

# MySQL Database
mysql -h localhost -u root -p65vu7sgeKkEQaXGb < ${DIR}/configurate/config/scholarship_owl.stg.sql

# Restart
service php7.2-fpm restart
service nginx restart
service mysql restart
service redis-server restart
