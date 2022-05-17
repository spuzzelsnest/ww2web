# Laravel and Leaflet Maps

Root dir change 

### Prerequisites

- php7
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
### Download and Config

Download into folder
```
git clone https://github.com/spuzzelsnest/ww2web.git ww2maps.loc 
```

Create missing directories
```
mkdir -p .core/bootstrap/cache 
mkdir -p .core/storage/logs
mkdir -p .core/storage/framework/sessions
mkdir -p .core/storage/framework/views
mkdir -p .core/storage/framework/cache/data
```

Change permissions
```
chmod 777 .core/storage/logs 
chmod 777 .core/storage/framework/sessions
chmod 777 .core/storage/framework/views
```

How to update a newly downloaded git
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
