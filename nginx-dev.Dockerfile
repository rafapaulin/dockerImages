FROM nginx:alpine

COPY ./nginx/vhost.conf /etc/nginx/conf.d/default.conf
COPY ./nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./nginx/ssl.cnf /etc/nginx/ssl/ssl.cnf
COPY ./nginx/ssl.crt /etc/nginx/ssl/ssl.crt
COPY ./nginx/ssl.csr /etc/nginx/ssl/ssl.csr
COPY ./nginx/ssl.key /etc/nginx/ssl/ssl.key

WORKDIR /var/www