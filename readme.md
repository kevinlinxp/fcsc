# fcsc
======

Foundations of Computer Science Competition built based on Laravel 5



# Installation
--------------
```sh
sudo apt-get install sqlite3
sudo apt-get install php5 php5-sqlite
sudo apt-get install git

curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

composer global require "laravel/installer=~1.1"

git clone https://github.com/cqqlin/fcsc
cd fcsc
composer install

vi database/seeds/StudentTableSeeder.php
touch /root/fcsc/storage/database.sqlite
composer dump-autoload
php artisan migrate:refresh --seed

php -S your.ip.address:port -t public &
```