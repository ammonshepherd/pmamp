# PMAMP - PhpMyadmin with Apache Mysql and Php

Docker with Apache, PHP, MySQL, phpMyAdmin

This set of images creates a container running an Apache Web server with a
MySQL database backend. PHP is the language of choice in this setup. A running
copy of phpMyAdmin is included for easy database administration.

This setup makes use of http://lvh.me, which is a free service that seamlessly
redirects lvh.me and any sub-domains back to your local computer, specifically
to 127.0.0.1. This makes the nice trick of having your project look like it is
being hosted at a real domain name. But be aware, nobody else in the world will
be able to see what you see at lvh.me. It will redirect them to their own
computer and likely result in an error message in the browser unless they have
a web server running on their computer.

You can also still use the IP address http://127.0.0.1 or http://localhost and
to access phpMyAdmin use http://pma.localhost

What does this set up contain?

- PHP/Apache image
  - PHP 7.4.14 with the following additional modules
    - gd
    - exif
    - ImageMagick (via imagick)
    - mysqli
    - pdo 
    - pdo_mysql
  - Apache 2.4.38 with mod_rewrite enabled
  - ImageMagick 
- phpMyAdmin image
  - phpMyAdmin 5.0.4 
  - PHP 7.4.13
  - Apache 2.4.38
- MySQL image
  - MySQL 8.0.22

## Prerequisites
- Install and run Docker Desktop
  - [https://www.docker.com/get-started ](https://www.docker.com/get-started)

## Run Docker images
On the command line (the terminal)
- Clone this repository where you want it.
  - `git clone `
- Change into the directory
- `cd pmamp`
- Change the MySQL account info in the `docker-compose.yml` file if you want
 
```
  MYSQL_ROOT_PASSWORD: "rootPASS"
  MYSQL_DATABASE: "dbase"
  MYSQL_USER: "dbuser"
  MYSQL_PASSWORD: "dbpass"
```

- The first time you run this, you will need to create a 'dbdata' folder
  - On the command line, issue the command: `mkdir dbdata`
  - Or create the folder in your Finder.app (MacOS) or Folder Explorer (Windows) application
- You will also need to create a new docker network
  - `docker network create traefikNetwork`
- Start the container
  - `docker compose up`
  - Or run it in the background to free up the terminal
    - `docker compose up -d`
- To stop the containers
  - press ctrl-c
  - then run `docker compose down`
- View the web pages at [http://localhost ](http://localhost), [http://lvh.me ](http://lvh.me) or
  [http://pmamp.lvh.me ](http://pmamp.lvh.me)
  - You can also edit the /etc/hosts file to allow for using existing domain
    names. For example, add the following to your /etc/hosts file:
    - `127.0.0.1 example.com`
    - How to change your /etc/hosts file:
      - ([Linux or Mac](https://www.makeuseof.com/tag/modify-manage-hosts-file-linux/)), 
      - or c:\windows\system32\drivers\etc\hosts ([Windows](https://www.howtogeek.com/howto/27350/beginner-geek-how-to-edit-your-hosts-file/)). 
    - Update the docker-compose.yml file to include the domain you added to the
      /etc/hosts file. Add the domain to the line like this:
      - ``"traefik.http.routers.php-apache.rule=Host(`lvh.me`, `fun.lvh.me`, `example.com`)"``

    - Now you can browse to [http://example.com ](http://example.com).
- View phpMyAdmin at [http://pma.lvh.me ](http://pma.lvh.me)
  - type in the db user name and db password to log in

## Database Connection
- Connect to the MySQL database with the following credentials:

  ```
    $server = 'mysql';
    $dbname = 'dbase';
    $username = 'dbuser';
    $password = 'dbpass';
    $dsn = "mysql:host=$server;dbname=$dbname";

  ```
  - The server/host/database url is `mysql` which is the name of the MySQL
    container. Because the PHP, Apache and Mysql are all in containers, they
    know to connect to each other through shortcut network names.

## General Notes 
- This will run four containers: a proxy container, a PHP-Apache container, a MySQL container and
a phpMyAdmin container.
- All of the files for the website building can go in the `www` folder.
- The database files are stored in the `dbdata` folder. This allows for the
  data to persist between restarts and for hands on access.
  - To restart with a clean database, just delete this folder.
  - To seed the database with a database, tables, and data, just uncomment the
    line in the docker-compose.yml file referencing `mysql_seed.sql`. The `dbdata`
    folder will need to be deleted first. This works best if using a mysql dump
    file. Otherwise, the sql file just needs to have valid SQL statments.
    - `#- ./mysql_seed.sql:/docker-entrypoint-initdb.d/mysql_seed.sql`


## Traefik Notes
This uses the Traefik image from here: https://hub.docker.com/_/traefik/
- Documentation is here: https://doc.traefik.io/traefik/
- You can have multiple domains and subdomains pointing to a single container
using the Hosts line in the label section of docker-compose.yml
    - ``"traefik.http.routers.php-apache.rule=Host(`lvh.me`, `fun.lvh.me`, `example.com`)"``

## lvh.me Notes
lvh.me is a free service that redirects to localhost, so now you can access the
site at http://lvh.me as well as http://localhost



# Software Updates
To update a specific software package to a different version, change the image
called in the docker-compose.yml or Dockerfile file. After any changes to
Dockerfile or docker-compose.yml you will need to run `docker compose build` or
add the --build flag the first time you run "docker compose up", like so
`docker compose up --build -d`

NOTE: When editing the Dockerfile, make sure to add a backslash (`\`) to any
lines that you add to the RUN command, unless it is the last line.

NOTE: Any changes to versions can totally break the setup. For example, at
present, ImageMagick is not available for PHP 8. To use PHP 8, you would need
to remove the line using pecl to install imagick in the Dockerfile. Other
changes to commands within the RUN line may need to change based on the version
you choose.


## PHP
For PHP, the image is set on the first line in the Dockerfile `FROM
php:7-apache` will grab the latest version of PHP 7. To get the latest version
of PHP 8, change the line to `FROM php:8-apache`. PHP developers set the
version of Apache. This can not be changed (easily). For more options, see the
offical DockerHub page [https://hub.docker.com/_/php ](https://hub.docker.com/_/php).

### PHP extensions
To add more PHP extensions, add the package to install in the list of packages
to install after the 'apt-get install' line (put them in alphabetical order).
Then add a 'docker-php-ext-install' line.
```
FROM php:7-apache

RUN apt-get update && apt-get install -y \
  imagemagick \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libmagickwand-dev --no-install-recommends \
  libpng-dev \
  --> add new software above this line (delete this line)
  && rm -rf /var/lib/apt/lists/* \
  && a2enmod rewrite \
  --> add new php extensions below this line (delete this line)
  && docker-php-ext-install exif \
  && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && docker-php-ext-install -j$(nproc) gd \
  && pecl install imagick && docker-php-ext-enable imagick \
  && docker-php-ext-install mysqli \
  && docker-php-ext-install pdo pdo_mysql

```

### PHP Settings
By default the file upload size and post limit size are set to 256MB. If you
need to change these values, edit the `uploads.ini` file.


## Apache
Apache is built into the PHP image used and can minimially be altered. To
install or enable modules, add a line after the list of packages in the
Dockerfile.

```
FROM php:7-apache

RUN apt-get update && apt-get install -y \
  imagemagick \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libmagickwand-dev --no-install-recommends \
  libpng-dev \
  && rm -rf /var/lib/apt/lists/* \
  --> add new a2enmod lines below this line (delete this line)
  && a2enmod rewrite \
  && docker-php-ext-install exif \
  && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && docker-php-ext-install -j$(nproc) gd \
  && pecl install imagick && docker-php-ext-enable imagick \
  && docker-php-ext-install mysqli \
  && docker-php-ext-install pdo pdo_mysql

```

## MySQL
Change the image line in docker-compose.yml. To use MySQL 5.6 use `image:
"mysql:5.6"` in the mysql section (about line 32). For more options, see the
official DockerHub page [https://hub.docker.com/_/mysql ](https://hub.docker.com/_/mysql).

## phpMyAdmin
For options, see the official DockerHub page
[https://hub.docker.com/_/phpmyadmin ](https://hub.docker.com/_/phpmyadmin).

- Note in particular the Environment Variables section to update the file
  upload size and post limit size for PHP. By default, this is set to 256MB.

## Additional software
To add aditional software, add it to the Dockerfile. Add packages to the list
of packages after the 'apt-get install' line in alphabetical order.

```
FROM php:7-apache

RUN apt-get update && apt-get install -y \
  imagemagick \
  libfreetype6-dev \
  libjpeg62-turbo-dev \
  libmagickwand-dev --no-install-recommends \
  libpng-dev \
  --> add new software above this line (delete this line)
  && rm -rf /var/lib/apt/lists/* \
  && a2enmod rewrite \
  && docker-php-ext-install exif \
  && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ && docker-php-ext-install -j$(nproc) gd \
  && pecl install imagick && docker-php-ext-enable imagick \
  && docker-php-ext-install mysqli \
  && docker-php-ext-install pdo pdo_mysql

```
