FROM nginx:latest

ADD nginx.conf /etc/nginx/conf.d/default.conf

ENTRYPOINT nginx -g "daemon off;"

EXPOSE 80