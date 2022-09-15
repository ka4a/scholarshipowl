# SOWL SQL Emails DB configuration

# Production configuration

SQL Root user password `ZSs7WhNEYAQ7hmM5`

## Setup DB and user SQL

CREATE DATABASE sowl_emails;

GRANT ALL ON sowl_emails.* TO 'scholarship_owl'@'%' IDENTIFIED BY 'bgw3HhisDuWhj8X28QmZ' WITH GRANT OPTION;

FLUSH PRIVILEGES;
