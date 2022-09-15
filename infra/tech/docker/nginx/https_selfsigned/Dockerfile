FROM nginx:stable

RUN apt-get update && apt-get install -y nginx-extras openssl && rm -rf /var/lib/apt/lists/*

COPY nginx.conf /etc/nginx/nginx.conf

RUN mkdir -p /etc/nginx/ssl && openssl req \            
  -newkey rsa:4096 -nodes -sha256 -keyout /etc/nginx/ssl/sowl.key \
  -x509 -days 365 -out /etc/nginx/ssl/sowl.crt \
  -subj "/C=US/ST=./L=./O=scholarshipowl/CN=*.dev.scholarshipowl.tech"

RUN chown -R www-data:www-data /etc/nginx/ssl

EXPOSE 80
EXPOSE 443

CMD ["nginx", "-g", "daemon off;"]
