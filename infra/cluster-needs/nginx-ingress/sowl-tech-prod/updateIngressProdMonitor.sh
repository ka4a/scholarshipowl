#!/bin/bash

helm upgrade \
    --set controller.stats.enabled=false \
    --set controller.ingressClass=nginx-monitor \
    --set controller.replicaCount=1 \
    --set controller.livenessProbe.timeoutSeconds=20 \
    --set controller.readinessProbe.timeoutSeconds=10 \
    --set controller.metrics.enabled=false \
    --set controller.service.loadBalancerIP=35.225.218.112 \
    --set controller.updateStrategy.type=RollingUpdate \
    --set controller.nodeSelector.node=prod \
    --set defaultBackend.nodeSelector.node=prod \
    --set defaultBackend.replicaCount=1 \
    --set serviceAccount.create=false \
    --set serviceAccount.name=nginx-ingress-monitor-serviceaccount \
    --namespace ingress-nginx-monitor  \
    --version 0.22.0 \
     nginx-ingress-monitor  \
     stable/nginx-ingress