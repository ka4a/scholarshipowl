#!/bin/bash

helm install -f values_new.yaml stable/grafana --name grafana --namespace grafana