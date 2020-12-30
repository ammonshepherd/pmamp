# PMAMP - PhpMyadmin with Apache Mysql and Php
- Docker with Apache, PHP, MySQL, phpMyAdmin

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

- The first time you run this, you will need to create a new docker network
  - `docker network create traefikNetwork`
- Start the container
  - `docker-compose up`
  - Or run it in the background to free up the terminal
    - `docker-compose up -d`
- To stop the containers
  - press ctrl-c
  - then run `docker-compose down`
- View the web pages at [http://lvh.me ](http://lvh.me) or
  [http://pmamp.lvh.me ](http://pmamp.lvh.me)
  - You can also edit the /etc/hosts file to allow for using existing domain
    names. For example, add the following to your /etc/hosts file:
    - `127.0.0.1 example.com`
    - How to change your /etc/hosts file:
      - ([Linux or Mac](https://www.makeuseof.com/tag/modify-manage-hosts-file-linux/)), 
      - or c:\windows\system32\drivers\etc\hosts ([Windows](https://www.howtogeek.com/howto/27350/beginner-geek-how-to-edit-your-hosts-file/)). 
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
  - The server/host/database url is 'mysql' which is the name of the MySQL container. Because the PHP, Apache and Mysql are all in containers, they know to connect to each other through shortcut network names.

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
    - "traefik.http.routers.php-apache.rule=Host(`lvh.me`, `fun.lvh.me`, `realdomain.com`)"

## lvh.me Notes
lvh.me is a free service that redirects to localhost, so now you can access the
site at http://lvh.me instead of http://localhost
