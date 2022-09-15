#!/bin/sh
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

if [ ! -f /var/www/html/index.php ]; then
	rm -rf /var/www/html/*
	cp -rf /usr/src/revive/* /var/www/html/
	find /var/www -type d -exec chmod 755 {} \;
	find /var/www -type f -exec chmod 644 {} \;
	chmod -R a+w /var/www/html/plugins
	chmod -R a+w /var/www/html/www/admin/plugins
	chmod -R a+w /var/www/html/www/images
	chown -R www-data:www-data /var/www
fi

exec "$@"
