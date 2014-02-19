#!/usr/bin/env bash

apt-get update

apt-get install -y apache2 libapache2-mod-php5 curl
apt-get install -y php5 php5-mysql php-pear php5-curl php5-gd php5-imagick php5-mcrypt php5-memcache php5-xdebug php-apc

export DEBIAN_FRONTEND=noninteractive
apt-get -y install mysql-server mysql-client-core-5.5

rm -rf /var/www
ln -fs /vagrant /var/www

a2enmod rewrite

# Ubuntu & Apache set AllowOverride None, so that needs to change
sed -i '/AllowOverride None/c AllowOverride AuthConfig FileInfo Indexes Limit Options=All,MultiViews' /etc/apache2/sites-available/default

service apache2 restart

mysql -uroot --execute="CREATE DATABASE foos;"
mysql -uroot -D foos < /vagrant/database/schema.sql

cp /vagrant/app/Config/database.php.default /vagrant/app/Config/database.php

sed -i "/'host' => 'localhost'/c \\\t\t'host' => '127.0.0.1'," /vagrant/app/Config/database.php
sed -i "/'login' => 'user'/c \\\t\t'login' => 'root'," /vagrant/app/Config/database.php
sed -i "/'password' => 'password'/c \\\t\t'password' => ''," /vagrant/app/Config/database.php
sed -i "/'database' => 'database_name'/c \\\t\t'database' => 'foos'," /vagrant/app/Config/database.php

