# Install queue manager

## Install "supervisord"

Supervisor will run and restore if failed `php artisan queue:work`

 - `sudo apt-get install supervisor`
 - Copy `config/queue/queue.supervisord.conf` to `/etc/supervisor/conf.d` and change to correct paths and environment
 - Restart supervisord `sudo service supervisor restart`