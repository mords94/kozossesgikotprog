FROM php:7.0-fpm-alpine

RUN apk update \
 && apk add zip unzip ssmtp \
 && apk add --virtual .build-deps git freetype-dev libjpeg-turbo-dev libmcrypt-dev libpng-dev libxml2-dev libmcrypt-dev postgresql-dev \
 && docker-php-source extract \
 && git clone https://github.com/phpredis/phpredis.git /usr/src/php/ext/phpredis \
 && docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr \
 && docker-php-ext-install pgsql pdo_pgsql mysqli opcache gd  mcrypt bcmath soap phpredis \
 && phpdeps=$( \
      scanelf --needed --nobanner --recursive /usr/local/lib/php/extensions \
      | awk '{ gsub(/,/, "\nso:", $2); print "so:" $2 }' \
      | sort -u \
      | xargs -r apk info --installed \
      | sort -u) \
 && apk add --virtual .phpexts-rundeps $phpdeps \
 && apk del .build-deps \
 && docker-php-source delete

COPY etc/ /usr/local/etc/
COPY ssmtp/ /etc/ssmtp/

CMD ["/usr/local/sbin/php-fpm", "-F"]
