#!/bin/bash

# $1 - kubePath
# $2 - branchName
# $3 - namespace
# $4 - phpImageTagNumber
# $5 - mysqlImage
# $6 - mysqlImageTag
# $7 - phpImage

echo "Helm Initializing"

chartPath="$1/charts/sowl-dev"

helm init
helm init --upgrade

exist=$(kubectl get po | grep "$2")
if [ ! -z "$exist"  ]; then
  # Upgrade
  helm upgrade $2 $chartPath \
  --namespace $3 \
  --set app=$2 \
  --set containers.phpfpm.repository=$7 \
  --set containers.phpfpm.tag=$4 \
  --set mysql.image=$5 \
  --set mysql.imageTag=$6
else
  # Installation
  helm install $chartPath --name-template $2 \
  --namespace $3 \
  --set app=$2 \
  --set containers.phpfpm.repository=$7 \
  --set containers.phpfpm.tag=$4 \
  --set mysql.image=$5 \
  --set mysql.imageTag=$6
fi
