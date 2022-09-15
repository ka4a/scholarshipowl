#!/bin/bash

DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

sudo cp $DIR/config/php.opcache.ini /etc/php/7.0/mods-available/opcache.ini

# File upload PHP max file size
sudo sed -i -e "s/upload_max_filesize = 2M/upload_max_filesize = 32M/g" /etc/php/7.0/fpm/php.ini
