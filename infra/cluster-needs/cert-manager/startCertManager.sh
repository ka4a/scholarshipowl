#!/bin/bash
# set -x

helm install \
    --name cert-manager  \
    --set serviceAccount.create=false \
    --set serviceAccount.name=kube-cert-manager \
    --set nodeSelector.node=stable \
    --version 0.11 \
    --namespace kube-cert \
    stable/cert-manager