FROM existenz/webstack:7.4

RUN apk --no-cache --update add bash nano \
    php7-ctype \
    php7-mbstring \
    php7-dom \
    php7-xml \
    php7-session \
    php7-pdo \
    php7-tokenizer \
    php7-xmlwriter \
    php7-pdo_mysql \
    php7-simplexml \
    php7-fileinfo \
    composer

ADD ./nginx-supervisor.ini /etc/supervisor/conf.d/nginx-supervisor.ini

COPY nginx.conf /etc/nginx/nginx.conf

WORKDIR /var/www
