apiVersion: cert-manager.io/v1alpha2
kind: Certificate
metadata:
  name: wildcard.scholarshipowl.com
  namespace: default
spec:
  secretName: wildcard.scholarshipowl.com
  issuerRef:
    name: sowl-marketing-prod
    kind: ClusterIssuer
  commonName: '*.scholarshipowl.com'
  dnsNames:
    - '*.scholarshipowl.com'
  acme:
    config:
    - dns01:
         provider: prod-cloudflare
      domains:
      - '*.scholarshipowl.com'
