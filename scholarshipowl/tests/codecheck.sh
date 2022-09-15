#!/usr/bin/env bash

TEST_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
REPORT_DIR="$TEST_DIR/report";
APP_DIR="$( cd "$( dirname "${TEST_DIR}" )" && pwd )"

mkdir -p ${REPORT_DIR}

# PHP-CPD
${APP_DIR}/vendor/bin/phpcpd \
  --quiet \
  --log-pmd=${REPORT_DIR}/phpcpd.xml \
  ${APP_DIR}/app \
  ${APP_DIR}/lib

# PHP-MD
${APP_DIR}/vendor/bin/phpmd \
  ${APP_DIR}/app,${APP_DIR}/lib xml \
  ${APP_DIR}/phpmd-ruleset.xml \
  --reportfile ${REPORT_DIR}/phpmd.xml

# PHP-CS
${APP_DIR}/vendor/bin/phpcs \
  --standard=${APP_DIR}/phpcs-ruleset.xml \
  --tab-width=4 \
  --report-full \
  --report-checkstyle=${REPORT_DIR}/phpcs.xml \
   ${APP_DIR}/app

# PHP Metrics
${APP_DIR}/vendor/bin/phpmetrics \
  --quiet \
  --config=${APP_DIR}/phpmetrics.yml \
  ${APP_DIR}/app
