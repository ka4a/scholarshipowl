# config valid only for current version of Capistrano
lock '3.5.0'

set :application, 'scholarshipowl'
set :app_environment, 'staging'
# set :repo_url, 'git@gitlab.com:scholarshipowl/scholarshipowl.git'

# Default branch is :master
# ask :branch, `git rev-parse --abbrev-ref HEAD`.chomp

# Default deploy_to directory is /var/www/my_app_name
set :deploy_to, '/var/www/scholarshipowl/'

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

###### Git Local ######
# release id is just the commit hash used to create the tarball.
set :project_release_id, `git log --pretty=format:'%h' -n 1 HEAD`
# the same path is used local and remote... just to make things simple for who wrote this.
set :project_tarball_path, "/tmp/#{fetch(:application)}-#{fetch(:project_release_id)}.tar.gz"

# We create a Git Strategy and tell Capistrano to use it, our Git Strategy has a simple rule: Don't use git.
module NoGitStrategy
  def check
    true
  end

  def test
    # Check if the tarball was uploaded.
    test! " [ -f #{fetch(:project_tarball_path)} ] "
  end

  def clone
    true
  end

  def update
    true
  end

  def release
    # Unpack the tarball uploaded by deploy:upload_tarball task.
    context.execute "tar -xf #{fetch(:project_tarball_path)} -C #{release_path}"
    # Remove it just to keep things clean.
    context.execute :rm, fetch(:project_tarball_path)
  end

  def fetch_revision
    # Return the tarball release id, we are using the git hash of HEAD.
    fetch(:project_release_id)
  end
end

# Capistrano will use the module in :git_strategy property to know what to do on some Capistrano operations.
set :git_strategy, NoGitStrategy

# Finally we need a task to create the tarball and upload it,
namespace :deploy do
  desc 'Create and upload project tarball'
  task :upload_tarball do |task, args|
    tarball_path = fetch(:project_tarball_path)
    # This will create a project tarball from HEAD, stashed and not committed changes wont be released.
   `git archive -o #{tarball_path} HEAD`
    raise 'Error creating tarball.'if $? != 0

    on roles(:all) do
      upload! tarball_path, tarball_path
    end
  end
end

before 'deploy:updating', 'deploy:upload_tarball'
###### ! Git Local ! ######

namespace :deploy do

  after :updating, :dotenv do
    on roles(:app) do
      execute "cd #{release_path} && if [ -e .env.#{fetch(:app_environment)} ]; then cp .env.#{fetch(:app_environment)} .env; fi;"
    end
  end

  after :updating, :npminstall do
    on roles(:app) do
      execute "cd #{release_path} && /usr/bin/env npm install --quiet --production"
    end
  end

  before :updated, :cache do
    on roles(:app) do
      execute "cd #{release_path} && /usr/bin/env php artisan cache:clear"
      execute "cd #{release_path} && /usr/bin/env php artisan route:cache"
    end
  end

  after :published, :restart do
    on roles(:app) do
      execute "chmod g+w -R #{release_path}"
      execute "sudo /usr/sbin/service php7.2-fpm restart"
      execute "sudo /usr/sbin/service memcached restart"
      execute "cd #{release_path} && /usr/bin/env php artisan queue:restart"
    end
  end

  after :starting, 'composer:install_executable'

end
