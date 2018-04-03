FROM alpine:latest

WORKDIR /dev-database
VOLUME /dev-database

#### Packages ####
RUN apk update && apk upgrade
RUN apk add --update mysql \
	mysql-client && \
	rm -f /var/cache/apk/*

RUN mkdir /run/mysqld
ARG DB_ROOT_USER=root
ARG DB_ROOT_PW=root

RUN mysql_install_db --user=${DB_ROOT_USER} > /dev/null

#[mysqld]
#user = root
#datadir = /app/mysql
#port = 3306
#log-bin = /app/mysql/mysql-bin