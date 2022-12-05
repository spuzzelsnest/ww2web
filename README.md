# Laravel 9.42 and Leaflet Maps POI settings with pictures

### Prerequisites

- php 8.1
- mysql
- composer

> Aptitude
``` 
sudo apt install curl php-cli php-mbstring git unzip composer
```

> Pacman
```
sudo pacman -Syu php81 php81-fpm composer
```

> if composer is not directly available on the system, make sure you have a copy of composer.phar in the .core directory.
```
cd ~
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
mv composer.phar [rootdir]/.core
```


### Download and Config

Download into folder by adding the folder at the end.
```
git clone https://github.com/spuzzelsnest/ww2web.git ww2maps.loc 
```

Create missing directories and files.
```
mkdir -p .core/bootstrap/cache 
mkdir -p .core/storage/logs
mkdir -p .core/storage/framework/sessions
mkdir -p .core/storage/framework/views
mkdir -p .core/storage/framework/cache/data

touch .core/storage/logs/laravel.log
```

Change the permissions of the created folders. These need to have full read and write access for the cache files.
```
chmod 777 .core/storage/logs
chmod 777 .core/storage/framework/sessions
chmod 777 .core/storage/framework/views
```


Edit .core/.env.example and edit it to your neeeds.
```
cp .core/.env.example .core/.env
nano .core/.env
```


### Update a new install

```
composer update

```
or
```
php composer.phar update
```


Generate a new Key that will be added to the .env file.
```
php artisan key:generate
```


### Setup the database

Default migrations are already created in .core/database/migrations/ generated with the following command. They are here just as a usecase if new migrations need to be created.
```
php artisan make:migration create_types_table
```
you can edit the files in the directory .core/database/migrations/

Check the status of the migrations with the following command from the .core directory.
```
php artisan migrate:status
```

To migrate to the database use:
```
php artisan migrate
```

The needed seeders are available from the directory .core/database/seeders, seed as follows.
```
php artisan db:seed
```

To refresh the data after changes have been made, you can do so using:
```
 php artisan migrate:refresh --seed
```

### Display the app

Visit the page in your browser via http://localhost.
Add new POI via the Admin Page.