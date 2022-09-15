FROM gcr.io/sowl-tech/sowl-dev/php-builder:v.7.2.27

LABEL maintainer="andreyt@scholarshipowl.com" \
      description="This is dockerfile version for Jenkins(Kubernetes), SOWL project run" \
      version="1.1.0" \
      release-date="2020-02-10"

WORKDIR /var/www/html
ENV FILE_PATH kube/docker/php-fpm

COPY ${FILE_PATH}/php.ini /usr/local/etc/php/php.ini
COPY ${FILE_PATH}/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY ${FILE_PATH}/entrypoint.sh /entrypoint.sh
RUN DEBIAN_FRONTEND=noninteractive chmod +x /entrypoint.sh && \
	addgroup --system nginx && adduser nginx --disabled-login --disabled-password --quiet --gecos '' --ingroup nginx && \
	mkdir -p /var/log/php-fpm && \
    touch /var/log/php-fpm/www-slow.log && \
    chown nginx:nginx /var/log/php-fpm/www-slow.log && \
    chmod -R 777 /var/log/php-fpm

COPY . /usr/src/html/
ENTRYPOINT ["/entrypoint.sh"]