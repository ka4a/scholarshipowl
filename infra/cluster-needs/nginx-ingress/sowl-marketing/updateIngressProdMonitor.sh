#!/bin/bash

helm upgrade \
    --set controller.stats.enabled=false \
    --set controller.ingressClass=nginx-monitor \
    --set controller.replicaCount=1 \
    --set controller.livenessProbe.timeoutSeconds=20 \
    --set controller.readinessProbe.timeoutSeconds=10 \
    --set controller.metrics.enabled=false \
    --set controller.service.loadBalancerIP=35.240.124.63 \
    --set controller.updateStrategy.type=RollingUpdate \
    --set controller.nodeSelector.node=stable \
    --set defaultBackend.nodeSelector.node=stable \
    --set defaultBackend.replicaCount=1 \
    --set serviceAccount.create=false \
    --set serviceAccount.name=nginx-ingress-monitor-serviceaccount \
    --namespace ingress-nginx-monitor  \
    --version 0.30.0 \
     nginx-ingress-monitor  \
     stable/nginx-ingress