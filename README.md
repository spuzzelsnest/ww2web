# Laravel and Leaflet Maps

### Prerequisites

- php 7.4 
- mysql
- composer

> Aptitude
``` 
sudo apt install curl php-cli php-mbstring git unzip composer
```

> Pacman
```
sudo pacman -Syu php7 php7-fpm composer
```

> if composer is not available directly
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
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

Change permissions.
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
php artisan key:generate
```

Setup database
```
php artisan make:migration create_types_table
php artisan make:migration create_footages_table
```

Edit the created database files and migrate to the database
```
php artisan migrate
```

A typesSeeder is available in .core/database/seeds, seed as follows.
```
php artisan db:seed
```
