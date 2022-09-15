FROM jenkins/jnlp-slave:3.27-1-alpine

USER root

ENV HELM_VERSION="v2.14.0"
ENV PATH $PATH:/home/jenkins/google-cloud-sdk/bin

RUN apk update && apk upgrade && apk --no-cache add \
    nodejs-current \
    nodejs-npm \
    curl \
    python \
    py-crcmod \
    bash \
    libc6-compat \
    openssh-client \
    git \
    gnupg \
    unzip \
    docker \
    util-linux \
    pciutils \
    usbutils \
    coreutils \
    binutils \
    findutils \
    grep && \
    wget https://dl.google.com/dl/cloudsdk/channels/rapid/google-cloud-sdk.zip && \
    unzip google-cloud-sdk.zip && \
    rm google-cloud-sdk.zip && \
    google-cloud-sdk/install.sh \
    --usage-reporting=false \
    --path-update=true \
    --bash-completion=true \
    --additional-components \
    beta \
    kubectl && \ 
    gcloud --version && \
    /home/jenkins/google-cloud-sdk/bin/gcloud config set --installation component_manager/disable_update_check true && \
    sed -i -- 's/\"disable_updater\": false/\"disable_updater\": true/g' /home/jenkins/google-cloud-sdk/lib/googlecloudsdk/core/config.json 

RUN wget -q https://storage.googleapis.com/kubernetes-helm/helm-${HELM_VERSION}-linux-amd64.tar.gz -O - | tar -xzO linux-amd64/helm > /usr/local/bin/helm \
    && chmod +x /usr/local/bin/helm
