# Testing SOWL

## Configuration for DB

```
CREATE DATABASE testing_sowl;
CREATE USER 'homestead'@'localhost' IDENTIFIED BY 'secret';
GRANT ALL ON testing_sowl.* TO 'homestead'@'localhost' WITH GRANT OPTION;
FLUSH PRIVILEGES;
```


## Generating Testing DB from Production

To generate dump for testing database
```
./tests/scripts/testing_sowl_dump.sh
```

Or to run unit tests, but before generating a new dump and applying it

```
./tests/phpunit.docker.sh --create-dump --seed
```