FROM nginx:1.16.1-alpine

COPY nginx.conf mime.types /etc/nginx/
RUN mkdir -p /etc/nginx/cache

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]