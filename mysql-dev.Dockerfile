FROM nginx:alpine

#### User/groups ####
RUN set -x ; \
	addgroup -g 82 -S www-data ; \
	adduser -u 82 -D -S -G www-data www-data && exit 0 ; exit 1
#### End of User/groups ####

COPY ./nginx/vhost.conf /etc/nginx/conf.d/vhost.template
COPY ./nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./nginx/ssl.cnf /etc/nginx/ssl/ssl.cnf
COPY ./nginx/ssl.crt /etc/nginx/ssl/ssl.crt
COPY ./nginx/ssl.csr /etc/nginx/ssl/ssl.csr
COPY ./nginx/ssl.key /etc/nginx/ssl/ssl.key


RUN mkdir -p /var/www/html \
	chown -p www-data:www-data /var/lib/nginx \
	chown -p www-data:www-data /var/www/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]