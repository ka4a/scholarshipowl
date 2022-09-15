#!/bin/bash

# $1 - user
# $2 - path
# $3 - userGroup
# $4 - CA crt path
# $5 - CA key path

openssl genrsa -out "$2/$1".key 2048
openssl req -new -key "$2/$1".key -out "$2/$1".csr -subj "/CN=$1/O=$3"
openssl x509 -req -in "$2/$1".csr -CA "$4" -CAkey "$5" -CAcreateserial -out "$2/$1".crt