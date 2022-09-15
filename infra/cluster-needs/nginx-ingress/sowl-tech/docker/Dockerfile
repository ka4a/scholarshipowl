FROM nginx:stable-alpine

COPY nginx.conf /etc/nginx/nginx.conf
RUN mkdir -p /var/www/html
COPY index.html /var/www/html

EXPOSE 8080
CMD ["nginx", "-g", "daemon off;"]