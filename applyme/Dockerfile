FROM alpine
WORKDIR /var/www/html
COPY . /var/www/html

# Concatenated RUN commands
RUN apk add --update nginx autoconf automake libtool nasm curl libpq libpng-dev linux-headers gcc g++ make python python-dev php7 php7-fpm php7-xmlwriter php7-fileinfo php7-curl php7-dom php7-phar php7-mbstring php7-session php7-json php7-pdo php7-openssl php7-tokenizer php7-pdo php7-pdo_mysql php7-xml php7-simplexml

# NODE JS
RUN mkdir -p /usr/local/lib/nodejs
# Install latest LTS node version
RUN echo "@edge http://nl.alpinelinux.org/alpine/edge/main" >> /etc/apk/repositories
RUN apk add -U nodejs@edge libuv@edge
# Install latest NPM
RUN curl -s -0 -L npmjs.org/install.sh | sh
# Install Yarn
RUN npm i -g yarn
RUN yarn config set registry http://registry.npmjs.org/

# Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php && \
    php composer-setup.php --install-dir=/usr/bin --filename=composer && \
    rm -rf composer-setup.php

RUN composer install --no-scripts
RUN yarn --ignore-engines
RUN yarn run prod

COPY nginx.conf /etc/nginx/nginx.conf
RUN chown -R nobody:nobody . \
    && rm -rf /var/cache/apk/*

RUN mkdir -p /run/nginx
EXPOSE 80
CMD php-fpm7 && nginx -g 'daemon off;'