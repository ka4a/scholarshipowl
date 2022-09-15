# Install Sowl On Ubuntu 14.04

## Nginx

```
aptitude install nginx
```

## PHP-FPM

```

aptitude update

aptitude install \
    php7.0 \
    php7.0-mbstring \
    php7.0-gd \
    php7.0-zip \
    php7.0-curl \
    php7.0-imap \
    php7.0-json \
    php7.0-xml \
    php7.0-dom \
    php7.0-ldap \
    php7.0-mcrypt \
    php7.0-soap \
    php7.0-mysql \

```

## MySQL

```

aptitude install mysql-server # password: M4ElpojNx9sv9SUT

mysql --user="root" --password="M4ElpojNx9sv9SUT" -e "CREATE USER 'scholarship_owl'@'%' IDENTIFIED BY 'bgw3HhisDuWhj8X28QmZ';"
mysql --user="root" --password="M4ElpojNx9sv9SUT" -e "GRANT ALL ON scholarship_owl.* TO 'scholarship_owl'@'%' WITH GRANT OPTION;"
mysql --user="root" --password="M4ElpojNx9sv9SUT" -e "FLUSH PRIVILEGES;"

service mysql restart

```

## Redis

```

aptitude install redis-server


```

Set password in `/etc/redis/redis.conf` to `requirepass Ctf7BBPW3rzhEEGe`

## Queue

### Install "supervisord"

Supervisor will run and restore if failed `php artisan queue:work`

 - `sudo apt-get install supervisor`
 - Copy `docs/config/queue.supervisord.conf` to `/etc/supervisor/conf.d` and change to correct paths and environment
 - Restart supervisord `sudo service supervisor restart`
 

## Deployment

### Create user `deploy`

  - Create user deploy assigned to www-data group `useradd -m -g www-data deploy`
  - Provide ssh-keys for authorization for deploy user
  - Provide write permissions to /var/www folder `chmod -R 775 /var/www && chown -R www-data:www-data /var/www`
