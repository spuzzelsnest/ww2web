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
mkdir .core/bootstrap/cache 
mkdir -p .core/storage/framework/sessions/ 
```

Change permissions
```
chmod -R 777 .core/storage 
chmod -R 777 .core/storage/framework/sessions/
```

### New update

How to update a newly downloaded git
```
composer update
php artisan key:generate
```
