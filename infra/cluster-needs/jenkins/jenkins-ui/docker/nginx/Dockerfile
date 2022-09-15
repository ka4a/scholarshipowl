FROM nginx:stable

RUN apt-get update && apt-get install -y certbot nginx-extras

COPY letsencrypt /etc/letsencrypt
COPY nginx.conf /etc/nginx/nginx.conf
RUN find /etc/letsencrypt/ -type f -exec chmod 644 {} \; && chown -R www-data:www-data /etc/letsencrypt

EXPOSE 80
EXPOSE 443

CMD ["nginx", "-g", "daemon off;"]
