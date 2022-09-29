FROM webdevops/php-nginx:8.1-alpine

ENV PHP_MAX_EXECUTION_TIME=1000
ENV php.max_input_time=1000
ENV PHP_DATE_TIMEZONE=Europe/Kiev
ENV PHP_UPLOAD_MAX_FILESIZE=50M
ENV PHP_POST_MAX_SIZE=50M
ENV PHP_MEMORY_LIMIT=-1

ENV WEB_DOCUMENT_ROOT=/app/public
ENV PHP_DISMOD=bz2,calendar,exiif,ffi,intl,gettext,mysqli,imap,sockets,sysvmsg,sysvsm,sysvshm,shmop,xsl,apcu,vips,yaml,mongodb,amqp
WORKDIR /app
COPY . .
COPY docker/php-nginx /opt/docker

RUN su application -c "echo '* * * * * cd /app && /usr/local/bin/php /app/artisan schedule:run >>/dev/null 2>&1' | crontab -"


# Ensure all of our files are owned by the same user and group.
RUN chown -R application:application .
