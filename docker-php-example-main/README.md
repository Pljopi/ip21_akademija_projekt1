# PHP Nginx Mysql Docker example

Simple environment for PHP - used by students

## Includes

### PHP-FPM

Implementation of php.

### Nginx

Nginx (pronounced "engine X" is one of the most popular open source **web servers**. The other popular choice is Apache.

### MySQL

MySQL is an open-source **relational database** management system (RDBMS).

### PhpMyAdmin

Open source administration tool for MySQL, used by developers only.

Access by going to:

    http://localhost:8080

## How to setup

1. Install `docker` and `docker-compose` (see online instructions for your system)
2. Copy all the files except this README into your project.
3. Run `docker-compose up -d` in your project

## How to use

You can see the output of `public/index.php` here:

    https://localhost

## Entering the container shell(s)

### Entering PHP shell

    docker-compose exec php_app bash

Where you can:

1. Run composer
2. Run scripts (like Laravels artisan)

### Entering Database shell

    docker-compose exec php_app_db bash

Where you can:

1. Access the database with `mysql -uroot -proot`

## Start/Stop docker

Start with:

    docker-compose up -d

Stop with:

    docker-compose stop

Rebuild dockers:

    docker-compose up -d --build

See running dockers:

    docker ps

View logs of a container (mysql, nginx, workspace, redis, ...)

    docker-compose logs -f {container-name}
