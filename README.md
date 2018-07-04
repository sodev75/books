#Books

## Installation


add 127.0.0.1 books.local.com in /etc/hosts

clone your project in /var/www
cd /var/www/books
cp .env.dev .env
add your IP here REMOTE_HOST=IP HERE
and your user id DOCKER_USER_ID=USER_ID_HERE

docker-compose up -d

and go to books.local.com

## Access containers

Docker php  : docker-compose exec php bash;
Docker PostGreSQL : docker-compose exec db psql -U postgres books_platform

##style css

modify your css in /assets/css

and build css run this command  sudo ./node_modules/.bin/encore dev