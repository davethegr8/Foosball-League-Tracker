#!/usr/bin/env bash

apt-get update

apt-get install -y apache2 libapache2-mod-php5 curl
apt-get install -y php5 php5-mysql php-pear php5-curl php5-gd php5-imagick php5-mcrypt php5-memcache php5-xdebug php-apc
apt-get install mysql-client-core-5.5

rm -rf /var/www
ln -fs /vagrant /var/www

a2enmod rewrite

sudo usermod -a -G vagrant www-data

# TODO: Ubuntu & Apache set AllowOverride None, so that needs to change
# sudo nano /etc/apache2/sites-enabled/000-default

/etc/init.d/apache2 restart

