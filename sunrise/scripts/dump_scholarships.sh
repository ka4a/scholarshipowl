#!/usr/bin/env bash

ssh pavelz@104.199.68.162 mysqldump --set-gtid-purged=OFF -h 104.155.67.76 -u scholarship_owl -pM4ElpojNx9sv9SUT scholarship_owl \
  scholarship \
  scholarship_status \
  eligibility \
  field \
  citizenship \
  ethnicity \
  country \
  state \
  school_level \
  degree \
  degree_type \
  military_affiliation \
  career_goal \
  requirement_name \
  requirement_text \
  requirement_file \
  requirement_image \
  requirement_input \
> scholarship.sql

#ssh pavelz@104.199.68.162 mysqldump --set-gtid-purged=OFF --no-data -h 104.155.67.76 -u scholarship_owl -pM4ElpojNx9sv9SUT scholarship_owl \
#  application \
#  application_file \
#  application_image \
#  application_input \
#  application_text \
#  account \
#  profile \
#>> scholarship.sql
