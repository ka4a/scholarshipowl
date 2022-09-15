#!/bin/bash
set -e

# first arg is `-f` or `--some-option`
if [ "${1#-}" != "$1" ]; then
	set -- php-fpm "$@"
fi

service memcached start

echo "Copy website"
cp -rf /usr/src/html/. /var/www/html

echo "Copy keys"

if [[ -d "/var/run/secrets/sunrise" ]]; then
    cp -f /var/run/secrets/sunrise/passport-keys/* storage
    cp -f /var/run/secrets/sunrise/sa/* storage
    cp -f /var/run/secrets/sunrise/gc_sa/* storage
    chmod 400 storage/oauth-*.key
    chmod 400 storage/*.json
fi

echo "Composer install post install actions"
composer install

echo "Yarn install"
npm i


echo "Rights..."
chmod -R 777 node_modules
chmod -R 777 storage
chmod -R 777 bootstrap/cache

echo "Install passport keys if not installed"
php artisan passport:keys

echo "Refresh PHP cache"
php artisan view:clear
php artisan config:cache
php artisan route:cache

# Replica amount should be equal to pod amount + 1
#replica=3
#success=0
#while [ $replica -gt 0 ]; do
#	wget -q "https://@DOMAIN/@FILE" -P /var/www/html/public
#	if [ $(cat "/var/www/html/public/@FILE" | grep "DONE") ]; then
#		success=1
#		break
#	fi
#	replica=$((replica - 1))
#done

#if [ $success -eq 1 ]; then
#    echo "Migration was already applied"
#else
#    echo "Running migration"
#    php artisan doctrine:migration:migrate --force
#    #Important. Create file for migration true flag
#    echo "DONE" > /var/www/html/public/@FILE
#fi

echo "Run migrations"
php artisan doctrine:migration:migrate --force
php artisan doctrine:generate:proxies

# Setup sunrise application
php artisan sunrise:setup

echo "Chown"
chown -R www-data:www-data /var/www/html

#Important. Create file for health check
touch /var/www/html/healthy
echo "OK"

exec "$@"
