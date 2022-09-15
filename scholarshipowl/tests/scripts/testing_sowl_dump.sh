#!/bin/bash

CURRENT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
DUMP_NAME="${CURRENT_DIR}/testing_sowl.schema.sql"
DB_HOST=mysql
DB_USER=scholarship_owl

for i in "$@"
do
case $i in
    --host=*|-h=*)
    DB_HOST="${i#*=}"
    shift # past argument=value
    ;;
    --usert=*|-u=*)
    DB_USER="${i#*=}"
    shift # past argument=value
    ;;
    *)
          # unknown option
    ;;
esac
done

mysqldump -h ${DB_HOST} -u${DB_USER} -psecret --no-data scholarship_owl --skip-comments | sed 's/ AUTO_INCREMENT=[0-9]*\b//g' > "$DUMP_NAME"
#mysqldump -h ${DB_HOST} -u${DB_USER} -psecret --no-data sowl_emails --skip-comments | sed 's/ AUTO_INCREMENT=[0-9]*\b//g' >> "$DUMP_NAME"

mysqldump -h ${DB_HOST} -u${DB_USER} -psecret --no-create-info --skip-comments scholarship_owl \
    admin_role_permission \
    application_status \
    application_essay_status \
    account_status \
    account_type\
    account_file_type \
    account_file_categories \
    accounts_favorite_scholarships \
    requirement_name \
    braintree_account \
    country \
    scholarship_status \
    state \
    citizenship \
    ethnicity \
    school_level \
    highschool \
    college \
    setting \
    degree \
    degree_type \
    email \
    career_goal \
    field \
    payment_method \
    package \
    subscription_status \
    subscription_acquired_type \
    transaction_status \
    transactional_email \
    transaction_payment_type \
    military_affiliation \
    domain \
    mobile_push_notification_settings \
    payment_fset_history \
    applyme_settings \
>> "$DUMP_NAME"
