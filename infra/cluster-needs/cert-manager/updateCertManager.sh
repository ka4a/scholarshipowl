#!/bin/bash
# set -x

helm upgrade \
    --set serviceAccount.create=false \
    --set serviceAccount.name=kube-cert-manager \
    --set nodeSelector.node=stable \
    --namespace cert-manager \
    --version 0.11.0 \
    cert-manager \
    jetstack/cert-manager
