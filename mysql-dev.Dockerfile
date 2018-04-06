FROM alpine:latest

WORKDIR /dev-database
VOLUME /dev-database

#### Packages ####
RUN apk update && apk upgrade
RUN apk --update --no-cache add mysql \
	mysql-client \
	&& addgroup mysql mysql \
	&& mkdir /scripts \
	&& rm -rf /var/cache/apk/*

VOLUME ["/var/lib/mysql"]

COPY ./mysql/startup.sh /scripts/startup.sh

RUN chmod +x /scripts/startup.sh

EXPOSE 3306

ENTRYPOINT ["/scripts/startup.sh"]