FROM gcr.io/sowl-tech/jenkins-builder
RUN curl --silent --location https://deb.nodesource.com/setup_8.x | bash -
RUN apt-get install --yes nodejs && rm -rf /var/lib/apt/lists/*
RUN npm install -g eslint
