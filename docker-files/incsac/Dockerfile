FROM isaackuang/swoole:4.4.1

RUN apk --no-cache --progress add \
    php7-json@php php7-tokenizer@php php7-mbstring@php \
    php7-redis@php php7-pdo@php php7-yaml@php php7-phar@php

RUN mkdir /opt && cd /opt && \
    wget https://github.com/swoft-cloud/swoft-cli/releases/download/v0.0.4/swoftcli.phar && \
    mv swoftcli.phar swoftcli && \
    chmod +x swoftcli

COPY docker-files/incsac/config /

COPY swoft /var/www/html