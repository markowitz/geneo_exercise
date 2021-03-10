FROM existenz/webstack:7.4

RUN apk --no-cache --update add bash nano \
    php7-ctype \
    php7-mbstring \
    php7-dom \
    php7-xml \
    php7-session \
    php7-pdo \
    php7-tokenizer \
    composer

ADD ./nginx-supervisor.ini /etc/supervisor/conf.d/nginx-supervisor.ini

COPY nginx.conf /etc/nginx/nginx.conf

WORKDIR /var/www