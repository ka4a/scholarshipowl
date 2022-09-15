#!/bin/bash

helm upgrade \
     --namespace monitoring \
     prometheus \
     -f values.yaml \
     stable/prometheus