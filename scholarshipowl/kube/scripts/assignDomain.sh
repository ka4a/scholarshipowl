#!/bin/bash

# $1 - branchName
# $2 - domainZone
# $3 - mainDomain
# $4 - serviceAccount
# $5 - serviceAccountKeyFile
# $6 - project

ip=$(kubectl get services | grep $1-$1 | awk {'print $4'})
if [ -z "$ip"  ]; then
  sleep 10
fi

$(gcloud auth activate-service-account $4 --key-file $5 --project $6)
domain=$(gcloud dns record-sets list -z=$2 | grep $1)
if [ -z "$domain" ];then
  $(gcloud dns record-sets transaction start -z=$2)
  $(gcloud dns record-sets transaction add -z=$2 --name="$1.$3" --type=A --ttl=300 "${ip}")
  $(gcloud dns record-sets transaction execute -z=$2)
fi
echo "\u2705 U C00L! Check out your branch here: http://$1.$3"
