#!/usr/bin/env bash

TEST_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
APP_DIR="$( cd "$( dirname "${TEST_DIR}" )" && pwd )"

${TEST_DIR}/scripts/testing_sowl.sh
${APP_DIR}/vendor/bin/phpunit --configuration phpunit.xml --cache-result-file storage/phpunit/.phpunit.result.cache
