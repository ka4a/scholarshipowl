#!/usr/bin/env bash

set -e

role=${CONTAINER_ROLE:-app}
env=${SRV_ENV:-production}

if [ "$env" = "init" ]; then
    echo "Copy files"
    cp -rf /usr/src/html/. /var/www/html/
    cp -f /.env.kubernetes /var/www/html/.env
    cp -f /database.php /var/www/html/config/database.php
    chmod -R 777 /var/www/html/storage
    chmod -R 777 /var/www/html/bootstrap/cache
    chown -R nginx:nginx /var/www/html
    echo "Copy Complete"

fi

if [ "$role" = "app" ]; then
	set -o allexport; . ./.env
    echo "Enable tideways"
    echo 'extension=tideways.so' >> /usr/local/etc/php/conf.d/40-tideways.ini
    echo 'tideways.connection=tcp://'$(echo $TIDEWAYS_ENDPOINT) >> /usr/local/etc/php/conf.d/40-tideways.ini
    echo 'tideways.api_key='$(echo $TIDEWAYS_APIKEY) >> /usr/local/etc/php/conf.d/40-tideways.ini
    echo 'tideways.framework=laravel' >> /usr/local/etc/php/conf.d/40-tideways.ini
	echo "Running app"
    php-fpm

elif [ "$role" = "dev" ]; then
	set -o allexport; . ./.env
    echo "Running migration..."
    php artisan migrate --force
    echo "Running app"
    php-fpm

elif [ "$role" = "queue" ]; then
	php artisan config:clear
    echo "Running the queue..."
    set -o allexport; . ./.env
    php artisan doctrine:queue:work redis --queue=notification --memory=512 --tries=1 &
    php artisan doctrine:queue:work redis --queue=payment_message --memory=512 --sleep=1 --tries=1 &
    php artisan doctrine:queue:work redis --queue=default --memory=512 --sleep=3 --tries=3
    

elif [ "$role" = "init" ]; then

    echo "Init Complete"
    exit 0

elif [ "$role" = "scheduler" ]; then

	set -o allexport; . ./.env
	echo "Run config cleaning"
	php artisan config:clear
	echo "Run scheduler"
	php artisan schedule:run --verbose --no-interaction
	echo "Scheduling completed"
	exit 0
    # while [ true ]
    # do
    #   php artisan schedule:run --verbose --no-interaction &
    #   sleep 60
    # done

else
    echo "Could not match the container role \"$role\""
    exit 1
fi