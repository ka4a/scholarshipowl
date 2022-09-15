#!/bin/bash

status=$(supervisorctl status | grep  init | awk {'print $2'})
while [ "$status" != "EXITED" ]; do
	status=$(supervisorctl status | grep  init | awk {'print $2'})
	echo "Waiting for init script to finish. Status: $status"
    if [ "$status" == "FATAL" ]; then
        echo "ERROR"
        exit 1
    fi
    sleep 5
done

#Important. Create file for health check
touch /var/www/html/healthy
echo 'Success'
# Starting php-fpm
php-fpm