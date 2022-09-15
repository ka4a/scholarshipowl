#!/bin/bash
# set -x

helm upgrade \
    --set serviceAccount.create=false \
    --set serviceAccount.name=kube-cert-manager \
    --set nodeSelector.node=prod \
    --namespace kube-cert \
    --version 0.5.2 \
    cert-manager \
    stable/cert-manager