# Local FTP

Installation guide for local FTP that used for testing uploading to FTP.

## Install and config
  - Install FTP Server `sudo apt-get install pure-ftpd`
  - Add FTP user `sudo adduser ftpman --home /var/ftpman/ --ingroup www-data`
  - Password for the user should be 'secret'
  - Run `php artisan uloop:export`
  - See new created file at /var/ftpman
 
