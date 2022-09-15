#!/bin/bash

jenkinsReleaseName="jenkins"
namespace="jenkins"

helm upgrade --install "$jenkinsReleaseName" . --namespace $namespace 

#helm install . --name "$jenkinsReleaseName" --namespace $namespace 