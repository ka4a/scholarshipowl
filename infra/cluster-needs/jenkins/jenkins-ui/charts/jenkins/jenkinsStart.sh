#!/bin/bash

jenkinsReleaseName="jenkins"
namespace="jenkins"

helm install . --name "$jenkinsReleaseName" --namespace $namespace 