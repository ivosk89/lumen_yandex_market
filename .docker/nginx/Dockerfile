FROM nginx:latest

COPY ./.docker/nginx/nginx.conf /etc/nginx/nginx.conf

RUN ln -sf /dev/stdout /tmp/nginx_access.log && ln -sf /dev/stderr /tmp/nginx_error.log

COPY ./app /var/www/html
WORKDIR /var/www/html

EXPOSE 8081

CMD ["nginx", "-g", "daemon off;"]
