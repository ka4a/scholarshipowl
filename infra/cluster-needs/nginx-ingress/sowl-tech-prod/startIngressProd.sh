#!/bin/bash

helm install stable/nginx-ingress \
    --name nginx-ingress \
    --namespace ingress-nginx  \
    --version 0.28.3 \
    --set controller.image.tag=0.20.0 \
    --set controller.stats.enabled=true \
    --set controller.ingressClass=nginx \
    --set controller.replicaCount=2 \
    --set controller.service.loadBalancerIP=35.224.24.189 \
    --set controller.livenessProbe.timeoutSeconds=20 \
    --set controller.readinessProbe.timeoutSeconds=10 \
    --set controller.metrics.enabled=true \
    --set controller.metrics.service.type=ClusterIP \
    --set controller.updateStrategy.type=RollingUpdate \
    --set controller.nodeSelector.node=prod \
    --set defaultBackend.nodeSelector.node=prod \
    --set defaultBackend.replicaCount=2 \
    --set serviceAccount.create=false \
    --set serviceAccount.name=nginx-ingress-serviceaccount