FROM php:7.2-fpm

ARG DOCKER_USER
ARG DOCKER_USER_ID
ARG TZ

# Installation
RUN apt-get update -qq \
RUN apt-get install -y --no-install-recommends gettext-base openssh-client wget git make zip cmake libssl-dev libxrender-dev libfontconfig-dev fontconfig libjpeg62-turbo libssl-doc libxml2-dev libcurl3-dev libxext6 xfonts-base libpq-dev xfonts-75dpi sudo \
RUN docker-php-ext-install -j$(nproc) zip bcmath mbstring xml curl pdo_pgsql soap \

# Install NPM
RUN curl -sL https://deb.nodesource.com/setup_7.x | bash - \
    && apt-get -y install nodejs

# Install XDEBUG
RUN pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

# Install Composer
RUN curl -sS http://getcomposer.org/installer | php -- --filename=composer \
    && chmod a+x composer \
    && mv composer /usr/local/bin/composer \
    && composer -q global require "hirak/prestissimo:^0.3"

# Add local user with .bashrc
RUN useradd --shell /bin/bash -u ${DOCKER_USER_ID} -o -c "" -m ${DOCKER_USER} \
    && adduser ${DOCKER_USER} sudo \
    && echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers \
    && export HOME=/home/${DOCKER_USER} \
    && mv /docker/.bashrc /home/${DOCKER_USER}/.bashrc \
    && chown ${DOCKER_USER}:${DOCKER_USER} /home/${DOCKER_USER}/.bashrc

# Run entrypoint
ENTRYPOINT ["/bin/bash", "/docker/entrypoint.sh"]
CMD ["php-fpm"]