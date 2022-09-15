#!/usr/bin/env bash

# to populate database and execute tests run:
#  ./tests/phpunit.docker.sh --seed

# to populate database applying a new dump, then execute tests run:
#  ./tests/phpunit.docker.sh --seed --create-dump

# to execute particular test, for example testReapplyAfterRecurrence:
# ./tests/phpunit.docker.sh --filter testSendApplicationWrongType ./tests/Test/Services/ApplicationServiceTest.php

TEST_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
APP_DIR="$( cd "$( dirname "${TEST_DIR}" )" && pwd )"
APPLY_DUMP=false
CREATE_DUMP=false

n=1;
for arg in "$@"
do

    case $arg in
        --seed)
        APPLY_DUMP=true
        set -- "${@:1:n-1}" "${@:n+1}"
        n=$((n - 1))
        ;;
        --create-dump)
        CREATE_DUMP=true
        set -- "${@:1:n-1}" "${@:n+1}"
        n=$((n - 1))
        ;;
        *)
        # unknown option
        ;;
    esac
    n=$((n + 1))
done

if [ "$CREATE_DUMP" == true ]; then
    echo "Creating a new dump..."
    ${TEST_DIR}/scripts/testing_sowl_dump.sh -h=mysql -u=scholarship_owl
fi

if [ "$APPLY_DUMP" == true ]; then
    echo "Applying dump..."
    ${TEST_DIR}/scripts/testing_sowl.sh --host=mysql_testing
fi

${APP_DIR}/vendor/bin/phpunit $@ --configuration phpunit.docker.xml --cache-result-file storage/phpunit/.phpunit.result.cache
