#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

service memcached start && service redis-server start

sleep 5 # wait for mysql to start
php artisan doctrine:queue:work redis --queue=default --memory=512 --sleep=3 --tries=3 > storage/logs/queue.`date +%Y-%m-%d`.log 2>&1 &

echo "OK"

exec "$@"
