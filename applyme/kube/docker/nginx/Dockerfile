FROM nginx:stable

ARG BUILD=production
ENV BUILD ${BUILD}

COPY nginx-${BUILD}.conf /etc/nginx/nginx.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
