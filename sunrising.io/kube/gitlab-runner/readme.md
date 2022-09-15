# Gitlab CI Runner

## Configuration

Please make sure that `config.yaml` has properly setted up `runnerRegistrationToken` got from GitLab CI runners settings.

## Setup

Add gitlab charts registry and init helm.

```bash
helm repo add gitlab https://charts.gitlab.io
helm init
```

Run next command to setup gitlab runner on your project namespace.

```bash
helm upgrade --install --wait --namespace scholarship-app -f ./kube/gitlab-runner/config.yaml scholarship-app-gitlab-runner gitlab/gitlab-runner
```