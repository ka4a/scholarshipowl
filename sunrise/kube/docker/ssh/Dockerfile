FROM ubuntu:16.04

RUN apt-get update && apt-get install -y wget vim curl git software-properties-common dialog mysql-client && rm -rf /var/lib/apt/lists/*
RUN LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php -y && apt-get update && apt-get install -y --allow-unauthenticated php7.2 php-pear php7.2-sqlite3 php7.2-mbstring php-mbstring php7.2-mbstring php7.2-dev php7.2-zip php7.2-curl php7.2-gd php7.2-mysql php-mcrypt php7.1-mcrypt php7.2-xml openssh-server && rm -rf /var/lib/apt/lists/*
RUN mkdir /var/run/sshd
RUN echo 'root:kXogrb9PGmQUmSi' | chpasswd
RUN sed -i 's/PermitRootLogin prohibit-password/PermitRootLogin yes/' /etc/ssh/sshd_config

# NodeJs
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash - && \
    apt-get install -y nodejs && rm -rf /var/lib/apt/lists/*

# Yarn
RUN curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - && \
    echo "deb https://dl.yarnpkg.com/debian/ stable main" | tee /etc/apt/sources.list.d/yarn.list && \
    apt-get update && apt-get install -y yarn && rm -rf /var/lib/apt/lists/*

# Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/local/bin --filename=composer && \
    rm -rf composer-setup.php

# SSH login fix. Otherwise user is kicked off after login
RUN sed 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' -i /etc/pam.d/sshd

ENV NOTVISIBLE "in users profile"
RUN echo "export VISIBLE=now" >> /etc/profile

RUN echo "cd /var/www/html" >> /root/.bashrc

EXPOSE 22
CMD ["/usr/sbin/sshd", "-D"]