## ScholarshipOwl - Web Application

A system to streamline the process of scholarship application for US students. Built on Laravel 5, using Vue.Js and previously React as the front-end library.

## Userful documentation

### Processes
[GitFlow](https://github.com/nvie/gitflow)

### Software libraries
[Laravel 5](https://laravel.com/docs/)]
[Vue.js](https://vuejs.org)

### Dependancy tools
[Composer](https://getcomposer.org/)
[Packages for Composer](https://packagist.org/)

### Communication tools
[Slack](http://scholarshipowl.slack.com)

### Internal tools
[Gitlab](https://gitlab.com/scholarshipowl/scholarshipowl)
[Jenknins](http://jenkins.scholarshipowl.tech/)

### Docker-compose
To run sowl project locally:
1. Change your .env settings to
DB_HOST=mysql
DB_DATABASE=scholarship_owl
DB_USERNAME=scholarship_owl
DB_PASSWORD=secret

2. In root folder run docker-compose up -d (It can take a while at the first run) (or docker-compose up -d --build) to rebuild images
3. Add in your /etc/hosts file this entry: "127.0.0.1 dev.sowl.com"
4. Visit the website on url: http://dev.sowl.com:8080
5. To close type docker-compose down
### Commands:
Use `docker exec php-fpm php artisan migrate` to migrate and `docker exec php-fpm composer install` to install composer dependencies
or use `docker exec -it php-fpm bash` to dive into container