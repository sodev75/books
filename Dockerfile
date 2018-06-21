# Image
FROM php:7.2-fpm

RUN \
    # Timezone
    echo "Europe/Paris" > /etc/timezone \
    && dpkg-reconfigure -f noninteractive tzdata \
    && echo 'date.timezone = "Europe/Paris"' > /usr/local/etc/php/conf.d/php.ini \

    # Installation
    && apt-get update -yqq \
    && apt-get install wget git curl libzip-dev zip libpq-dev libxrender-dev libfontconfig-dev fontconfig libjpeg62-turbo libxext6 xfonts-base xfonts-75dpi sudo -yqq \
    && docker-php-ext-configure zip --with-libzip \
    && docker-php-ext-install pdo pdo_pgsql zip \

    # PDF
    && wget https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.4/wkhtmltox-0.12.4_linux-generic-amd64.tar.xz \
    && tar xvf wkhtmltox-0.12.4_linux-generic-amd64.tar.xz \
    && sudo mv wkhtmltox/bin/wkhtmlto* /usr/bin/ \
    && rm -rf wkhtmltox \

    # Composer
    && curl -Ss https://getcomposer.org/installer | php \
    && ./composer.phar global require hirak/prestissimo
