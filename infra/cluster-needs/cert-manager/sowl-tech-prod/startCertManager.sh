#!/bin/bash
# set -x

helm install \
    --name cert-manager  \
    --set serviceAccount.create=false \
    --set serviceAccount.name=kube-cert-manager \
    --set nodeSelector.node=prod \
    --version 0.5.2 \
    --namespace kube-cert \
    stable/cert-manager
