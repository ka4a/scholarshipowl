#!/usr/bin/env bash

echo 'mysql-server mysql-server/root_password password 65vu7sgeKkEQaXGb' | debconf-set-selections
echo 'mysql-server mysql-server/root_password_again password 65vu7sgeKkEQaXGb' | debconf-set-selections

apt-get -y install mysql-server
