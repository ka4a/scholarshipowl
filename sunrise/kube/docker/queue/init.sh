#!/bin/bash
set -e
set -o pipefail 

while [ ! -f "/var/www/html/healthy"  ]; do
    echo "Health file not exist"
    sleep 5
done

if [ -f "/var/www/html/healthy" ]; then
    echo 'Starting supervisor'
    service supervisor start
    supervisorctl start all
    echo "Adding cron"
    echo "* * * * * www-data php /var/www/html/artisan schedule:run >> /dev/null 2>&1" >> /etc/crontab
    chown -R www-data:www-data /var/www/html/storage/logs/queue_default.log
fi