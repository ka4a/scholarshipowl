FROM nginx:stable

ENV APP_DOMAIN app.scholarship.app

COPY nginx.conf /etc/nginx/nginx.conf

# Replace
RUN if [ "${APP_DOMAIN}" != "app.scholarship.app" ]; then sed -i "s/app.scholarship.app/${APP_DOMAIN}/g" /etc/nginx/nginx.conf; fi

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]