#!/bin/bash

helm install stable/nginx-ingress \
    --name nginx-ingress-sowl \
    --namespace ingress-nginx-sowl  \
    --set controller.image.tag=0.17.1 \
    --set controller.stats.enabled=true \
    --set controller.ingressClass=nginx-sowl \
    --set controller.replicaCount=1 \
    --set controller.service.loadBalancerIP=104.155.5.61 \
    --set controller.livenessProbe.timeoutSeconds=20 \
    --set controller.readinessProbe.timeoutSeconds=10 \
    --set controller.metrics.enabled=true \
    --set controller.updateStrategy.type=RollingUpdate \
    --set controller.nodeSelector.node=stable \
    --set defaultBackend.nodeSelector.node=stable \
    --set defaultBackend.replicaCount=1 \
    --set serviceAccount.create=false \
    --set serviceAccount.name=nginx-ingress-serviceaccount