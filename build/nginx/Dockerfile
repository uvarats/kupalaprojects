FROM nginx

COPY ./nginx.conf /etc/nginx/nginx.conf
COPY ./default.conf /etc/nginx/conf.d/

WORKDIR /var/www/project

CMD ["nginx", "-g", "daemon off;"]
