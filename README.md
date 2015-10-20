fcsc
====
Foundations of Computer Science Competition built based on Laravel 5

Installation on a fresh Ubuntu 14.04 server:
--------------------------------------------
##### Install sqlite, php and git:
```sh
sudo apt-get install sqlite3
sudo apt-get install php5
sudo apt-get install php5-sqlite
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

##### Prepare database seeder:
```sh
vi database/seeds/StudentTableSeeder.php
```
Paste the following php script to the editor, and modify the data as needed:
```php
<?php

use Illuminate\Database\Seeder;

class StudentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = [
            ["id" => "id_001", "firstName" => "Louie", "lastName" => "Qin"],
            ["id" => "id_002", "firstName" => "Keith", "lastName" => "Lin"],
            ["id" => "id_003", "firstName" => "Minming", "lastName" => "Qian"],
        ];

        foreach ($students as $student) {
            DB::table('students')->insert([
                'id' => $student['id'],
                'firstName' => $student['firstName'],
                'lastName' => $student['lastName'],
                'lastPlayed' => \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '1970-01-01 00:00:00'),
                'highestMark' => 0,
                'recordDate' => \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', '1970-01-01 00:00:00')
            ]);
        }
    }
}

```

##### Prepare the .env file:
```sh
vi .env
```
Paste the following text to the editor, and modify the configiration as needed (For example, to disable the debug mode, which will show the secret number as you guess, please change to "APP_DEBUG=false". Apart from that, you also need to change END_DATE according your needs.):
```txt
APP_ENV=local
APP_DEBUG=true
APP_KEY=ffvR5FcYprwdZaHsMc4RMQASgzDLSBfF

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

MAIL_DRIVER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null

END_DATE=2018-10-18 13:30:00
DEDUCT_ROUND_POINT_INTERVAL=45
```

##### Set-up database:
```sh
touch storage/database.sqlite
composer dump-autoload
php artisan migrate:refresh --seed
```

##### Set-up encripted key:
```sh
php artisan key:generate
```


##### Start the service:
To test on local machine:
```sh
php -S localhost:8888 -t public
```

To run the service on a server and open to public:

```sh
php -S your.ip.address:port -t public &
```

or something like:

```sh
php -S 129.127.10.84:80 -t public &
```

##### Visit the site via:

http://localhost:8888

or

http://your.ip.address:port

or

http://129.127.10.84

