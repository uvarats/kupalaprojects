FROM php:8.2-fpm-alpine

RUN apk add shadow wget supervisor bash curl

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN chmod +x /usr/local/bin/install-php-extensions && \
    install-php-extensions gd pdo_mysql pdo_pgsql intl \
    opcache exif sockets zip mysqli pcntl event redis

RUN install-php-extensions @composer

COPY ./php.ini /usr/local/etc/php/conf.d/php.ini
COPY ./fpm.conf /usr/local/etc/php-fpm.d/www.conf

ENV NVM_DIR /usr/local/nvm
ENV NODE_VERSION 18.16.0
RUN mkdir $NVM_DIR

RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.3/install.sh | bash \
    && . $NVM_DIR/nvm.sh \
    && nvm install $NODE_VERSION \
    && nvm alias default $NODE_VERSION \
    && nvm use default

RUN node -v
RUN npm -v
RUN npm install --global yarn


RUN \
  mkdir /var/www/project && \
  mkdir /var/www/log && \
  touch /var/www/log/php-error.log && \
  touch /var/www/log/fpm-access.log


RUN chmod +x /var/www
RUN chown -R www-data:www-data /var/www

RUN addgroup -g 1000 node \
    && adduser -u 1000 -G node -s /bin/sh -D node

ARG UID=1000
ARG GID=1000
ENV UID=${UID}
ENV GID=${GID}
RUN usermod -u $UID node \
  && groupmod -g $GID node

USER node

WORKDIR /var/www/project


