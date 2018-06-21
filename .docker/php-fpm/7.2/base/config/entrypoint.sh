#!/bin/sh

set -e

shopt -s nocasematch

if [ -z ${TZ+x} ]; then
  echo "Warning : TimeZone is unset !";
else
  ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone ;
fi

# Moving static files to a sharedVolume with the nginx
rm -rf /nginx/*
if [ -d "/var/www/wedo-books/public" ]; then
    cp -rf /var/www/wedo-books/public /nginx
fi

# Execute migrations
su www-data -ms /bin/bash -c 'php /var/www/wedo-books/bin/console doctrine:migrations:migrate --no-interaction --env=prod'

exec "$@"
