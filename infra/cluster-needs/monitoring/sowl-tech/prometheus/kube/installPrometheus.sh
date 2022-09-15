#!/bin/bash

helm install stable/prometheus --name prometheus --namespace monitoring -f values.yaml