#!/bin/bash

helm install stable/nginx-ingress \
    --name nginx-ingress \
    --namespace ingress-nginx  \
    --set controller.stats.enabled=true \
    --set controller.ingressClass=nginx \
    --set controller.replicaCount=1 \
    --set controller.service.loadBalancerIP=35.190.57.229 \
    --set controller.livenessProbe.timeoutSeconds=15 \
    --set controller.metrics.enabled=true \
    --set controller.metrics.service.type=NodePort \
    --set controller.updateStrategy.type=RollingUpdate