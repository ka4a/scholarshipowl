# config valid only for current version of Capistrano
lock '3.5.0'

set :application, 'scholarshipowl'
set :app_environment, 'staging'
set :repo_url, 'git@gitlab.com:scholarshipowl/scholarshipowl.git'

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, '/var/www/scholarshipowl/'

# Speed up deploy
set :deploy_via, :remote_cache

# Default value for :scm is :git
# set :scm, :git

# Default value for :format is :pretty
# set :format, :pretty

# Default value for :log_level is :debug
# set :log_level, :debug

# Default value for :pty is false
# set :pty, true

# Default value for :linked_files is []
# set :linked_files, fetch(:linked_files, []).push('config/database.yml', 'config/secrets.yml')

# Default value for linked_dirs is []
set :linked_dirs, fetch(:linked_dirs, []).push('storage/logs', 'storage/framework/sessions')
set :linked_dirs, fetch(:linked_dirs, []).push('pubic/assets/js/build', 'pubic/assets/css/build')

# Default value for default_env is {}
# set :default_env, { path: "/opt/ruby/bin:$PATH" }

# Default value for keep_releases is 5
# set :keep_releases, 5

SSHKit.config.command_map[:composer] = "php #{shared_path.join("composer.phar")}"

def branch_name(default_branch)
  branch = ENV.fetch('BRANCH', default_branch)

  if branch == '.'
    # current branch
    `git rev-parse --abbrev-ref HEAD`.chomp
  else
    branch
  end
end

namespace :deploy do

  after :updating, :yarn_build do
    on roles(:prod, :queue) do
      execute "cd #{release_path} && /usr/bin/env yarn --silent"
      execute "cd #{release_path} && ./node_modules/.bin/gulp icon-font"
      execute "cd #{release_path} && ./node_modules/.bin/gulp sass"
      execute "cd #{release_path} && /usr/bin/env yarn run build"
      execute "cd #{release_path} && /usr/bin/env yarn run assets:all"
    end
  end

  after :updating, :prepare do
    on roles(:prod, :queue) do
      execute "sudo chmod g+w -R #{release_path}"
      execute "sudo chmod g+w -R #{deploy_to}shared"
      execute "sudo chown www-data:www-data -R #{release_path}"
      execute "sudo chown www-data:www-data -R #{deploy_to}shared"
      execute "cd #{release_path} && if [ -e .env.#{fetch(:app_environment)} ]; then cp .env.#{fetch(:app_environment)} .env; fi;"
      execute "cd #{release_path} && if [ -e .env ]; then echo 'DOCTRINE_CACHE=array' >> .env; fi;"
      execute "cd #{release_path} && if [ -e ./config/nginx-assets.conf ]; then sed -r 's/REVISION/#{fetch(:current_revision)}/g' config/nginx-assets.conf; fi;"
    end
  end

  after :updated, :prepare_app do
    on roles(:prod, :queue) do
      execute "cd #{release_path} && /usr/bin/env php artisan config:cache"
      execute "cd #{release_path} && /usr/bin/env php artisan route:cache"
      execute "cd #{release_path} && /usr/bin/env php artisan doctrine:generate:proxies --quiet"
    end
    on roles(:prod) do
        execute "cd #{release_path} && /usr/bin/env php artisan migrate --force"
    end
  end

  before :published, :restart do
    on roles(:prod, :queue) do
      execute "cd #{release_path} && /usr/bin/env php artisan cache:clear"
      execute "cd #{release_path} && /usr/bin/env php artisan queue:restart"
      execute "sudo /usr/sbin/service php7.2-fpm reload"
      execute "sudo /usr/sbin/service nginx reload"
      execute "cd #{release_path} && if [ -e .env.#{fetch(:app_environment)} ]; then cp .env.#{fetch(:app_environment)} .env; fi;"
    end
  end

  after :starting, 'composer:install_executable'

end
