#!/usr/bin/env bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

# Install requirements

apt-get -y update

${DIR}/install/php.sh
${DIR}/install/node.sh
${DIR}/install/nginx.sh
${DIR}/install/redis.sh
${DIR}/install/memcached.sh
${DIR}/install/beanstalkd.sh

# Configurate server
${DIR}/configurate/php.sh
${DIR}/configurate/nginx.sh 'scholarshipowl.com' /var/www/scholarshipowl/current/public
${DIR}/configurate/deploy.sh
${DIR}/configurate/redis.sh

# Restart
service php7.0-fpm restart
service nginx restart
service redis-server restart
