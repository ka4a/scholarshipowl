#!/bin/bash

helm upgrade \
     --namespace grafana \
     grafana \
     -f values.yaml \
     stable/grafana