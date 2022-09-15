#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

service memcached start && service redis-server start

echo "Copy website"
cp -rf /usr/src/html/. /var/www/html
echo "Rights..."
#find . -type f -exec chmod 664 {} \;
#find . -type d -exec chmod 775 {} \;
#chmod -R 777 node_modules
chmod -R 777 storage
chmod -R 777 bootstrap/cache
chown -R www-data:www-data /var/www/html

echo "OK"

exec "$@"
