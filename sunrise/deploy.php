<?php
namespace Deployer;

require 'recipe/laravel.php';
require 'recipe/yarn.php';

// Project name
set('application', 'sunrise');

// Project repository
set('repository', 'git@gitlab.com:scholarshipowl/sunrise.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
add('shared_files', []);
add('shared_dirs', []);

// Writable dirs by web server
add('writable_dirs', []);


// Hosts
host('sunrise.dev.scholarshipowl.com')
    ->user('deploy')
    ->stage('staging')
    ->roles(['app'])
    ->set('branch', 'develop')
    ->set('deploy_path', '/var/www/{{application}}');

// Tasks

//set('bin/npm', function () {
//    return run('which npm');
//});

desc('Doctrine build.');
task('deploy:doctrine', function() {
    // run('{{bin/php}} {{release_path}}/artisan doctrine:ensure:production');
    run('{{bin/php}} {{release_path}}/artisan doctrine:migrations:migrate');
    run('{{bin/php}} {{release_path}}/artisan doctrine:generate:proxies');
});

desc('Build JS Application');
task('yarn:build', function () {
    run("cd {{release_path}} && {{bin/yarn}} prod");
    // run("cd {{release_path}} && {{bin/yarn}} admin-prod");
});

desc('Restart servers');
task('restart', function() {
    run('sudo service supervisor restart');
    run('sudo service memcached restart');
    run('sudo service php7.2-fpm restart');
    run('sudo service nginx restart');
});

after('artisan:config:cache',    'artisan:route:cache');

// [Optional] if deploy fails automatically unlock.
after('deploy:failed',      'deploy:unlock');
after('deploy:writable',    'yarn:install');
after('deploy:writable',    'yarn:build');
after('deploy:writable',    'deploy:doctrine');
after('deploy:symlink',     'restart');


// Migrate database before symlink new release.

// before('deploy:symlink', 'artisan:migrate');

