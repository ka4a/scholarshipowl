DROP DATABASE IF EXISTS testing_sowl;
CREATE DATABASE testing_sowl;
CREATE USER IF NOT EXISTS 'testing_sowl'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL ON testing_sowl.* TO 'testing_sowl'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
SET GLOBAL sql_mode = '';