FROM php:7.2-fpm

# Install packages
RUN apt-get update -qq && apt-get install -y --no-install-recommends \
    gettext-base \
    openssh-client \
    wget \
    git \
    make \
    cmake \
    libssl-dev \
    libssl-doc \
    libxml2-dev \
    libcurl3-dev \
    zlib1g-dev \
    libxrender-dev \
    libfontconfig-dev \
    fontconfig \
    libjpeg62-turbo \
    libxext6 \
    xfonts-base \
    libpq-dev \
    xfonts-75dpi \
    software-properties-common \
    gnupg

# Install php extensions
RUN docker-php-ext-install -j$(nproc) \
    opcache \
    zip \
    bcmath \
    xml \
    pdo_pgsql

# Install PDF support
RUN wget https://github.com/wkhtmltopdf/wkhtmltopdf/releases/download/0.12.4/wkhtmltox-0.12.4_linux-generic-amd64.tar.xz \
    && tar xvf wkhtmltox-0.12.4_linux-generic-amd64.tar.xz \
    && mv wkhtmltox/bin/wkhtmlto* /usr/bin/ \
    && rm -rf wkhtmltox

### Install gcsfuse
RUN export GCSFUSE_REPO=gcsfuse-`lsb_release -c -s` \
    && echo "deb http://packages.cloud.google.com/apt $GCSFUSE_REPO main" | tee /etc/apt/sources.list.d/gcsfuse.list \
    && curl https://packages.cloud.google.com/apt/doc/apt-key.gpg | apt-key add - \
    && apt-get update \
    && apt-get install -y gcsfuse

### Add newrelic archive
RUN mkdir -p /opt/newrelic \
    && cd /opt/newrelic \
    && wget -r -nd --no-parent -Alinux.tar.gz http://download.newrelic.com/php_agent/release/ > /dev/null 2>&1 \
    && tar -xzf newrelic-php*.tar.gz --strip=1 && rm newrelic-php*.tar.gz && cd -

# Copy config
COPY config /docker/
ADD config/custom-php.ini /usr/local/etc/php/conf.d/custom-php.ini
