## fcsc
-------
Foundations of Computer Science Competition built based on Laravel 5

### Installation on a fresh Ubuntu 14.04 server:
-----------------------------------------------
##### Install sqlite, php and git:
```sh
sudo apt-get install sqlite3
sudo apt-get install php5 php5-sqlite
sudo apt-get install git
```

##### Install composer:
```sh
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

##### Install Laravel using composer:
```sh
composer global require "laravel/installer=~1.1"
```

##### Clone this project from git and install dependencies:
```sh
git clone https://github.com/cqqlin/fcsc
cd fcsc
composer install
```

##### Set-up database:
```sh
vi database/seeds/StudentTableSeeder.php
touch /root/fcsc/storage/database.sqlite
composer dump-autoload
php artisan migrate:refresh --seed
```

##### Start the service:
```sh
php -S your.ip.address:port -t public &
```
