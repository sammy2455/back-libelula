FROM composer:2.5.5
ARG UID
ARG GID
ENV UID=${UID}
ENV GID=${GID}
RUN delgroup dialout
RUN addgroup -g ${GID} --system yii2
RUN adduser -G yii2 --system -D -s /bin/sh -u ${UID} yii2
WORKDIR /var/www/html