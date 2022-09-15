#!/usr/bin/env bash

CURRENT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
DB_HOST=localhost

for i in "$@"
do
case $i in
    --host=*|-h=*)
    DB_HOST="${i#*=}"
    shift # past argument=value
    ;;
    *)
          # unknown option
    ;;
esac
done

mysql --user=root --host=${DB_HOST} -psecret < "${CURRENT_DIR}/testing_sowl.sql"
mysql --user=root --host=${DB_HOST} -psecret testing_sowl < "${CURRENT_DIR}/testing_sowl.schema.sql"
