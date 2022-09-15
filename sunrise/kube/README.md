# Kubernetes

## Setup gitlab docker registry authentication.

Create access token in gitlab profile settings and setup the secret for accessing the gitlab docker registry.

```bash
kubectl create secret docker-registry gitlab-registry --namespace sunrise-dev \
 --docker-server=registry.gitlab.com \
 --docker-username=PavelZh \
 --docker-password=YOUR_PERSONAL_GITLAB_ACCESS_TOKEN_HERE \
 --docker-email=pavelz@scholarshipowl.com
```