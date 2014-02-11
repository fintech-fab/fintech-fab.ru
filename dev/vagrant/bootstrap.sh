#!/bin/bash

#sudo cd /home/vagrant
#sudo sed -e 's/#umask 022/umask 002/g' ./.profile > tmp
#sudo mv tmp .profile
#sudo sed -i '1 i umask 002' ./.bashrc >> /dev/null
#sudo usermod -a -G www-data vagrant

ROOT=/var/www
PROJ=/var/www/fintech-fab.dev
sudo rm -rf $ROOT

sudo mkdir $ROOT
sudo chmod 775 $ROOT
sudo chown vagrant:www-data $ROOT
sudo chmod g+s $ROOT

mkdir $PROJ
cd $PROJ

git clone https://github.com/fintech-fab/fintech-fab.ru.git .
composer install
php artisan key:generate
php artisan ide-helper:generate
