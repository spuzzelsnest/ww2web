# ww2maps.net – WW2 footage on a Leaflet map

Laravel 13 app that shows WW2 press photos and film footage as markers on a Leaflet map.

## Layout

The repository root is the **web root**: `index.php`, `css/`, `js/`, `images/`, `media/`.
The Laravel app lives in the hidden `.core/` folder (it is blocked from the web by `.htaccess`).
`public_path()` points to the repository root (set in `.core/bootstrap/app.php`).

```
index.php            front controller
.htaccess            HTTPS redirect, blocks .core/ and dot-files, pretty URLs
css/ js/             static assets (Bootstrap, Leaflet, MarkerCluster)
images/ media/       footage files (not in git, live only on the server)
.core/
  app/Models         Footage, Type, Operation, Country, Source, User
  app/Http           controllers, SecurityHeaders middleware, form request
  app/Console        admin:create and database:backup commands
  bootstrap/app.php  routing, middleware and exception setup
  config/            only the values that differ from Laravel's defaults
  routes/web.php     map, login, admin
  tests/Feature      smoke tests
deploy.sh            one-command deploy to ww2maps.net
```

## Requirements

- PHP 8.3 or newer (the live site runs 8.4), with pdo_mysql, mbstring, gd
- MySQL / MariaDB
- Composer

## Local install

```
git clone https://github.com/spuzzelsnest/ww2web.git ww2maps.loc
cd ww2maps.loc/.core

composer install
cp .env.example .env          # then edit DB_* , CARTO_API_KEY, LAST_UPDATE
php artisan key:generate

mkdir -p storage/framework/{cache/data,sessions,views} storage/logs storage/app bootstrap/cache

php artisan migrate --seed     # tables plus the footage data
php artisan admin:create you@example.com   # admin login for /admin

php artisan serve              # http://127.0.0.1:8000
```

(`php artisan serve` serves `public_path()`, which is the repository root.)

## Tests

```
cd .core
php artisan test
```

## Updating dependencies

```
cd .core
composer update
php artisan test
git add composer.json composer.lock
```

Commit `composer.lock`: the deploy installs exactly the versions in it.

## Deploying to ww2maps.net

One-time setup:

1. Enable SSH in the one.com control panel and check you can log in:
   `ssh <user>@<host>`. Run `pwd` inside the web root to get its full path.
2. `cp deploy.env.example deploy.env` and fill in host, user and path
   (`deploy.env` is git-ignored).
3. Make sure `.core/.env` exists **on the server** with the production values:
   `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://ww2maps.net`,
   the DB settings, `SESSION_SECURE_COOKIE=true`.
4. On macOS, install a current rsync: `brew install rsync`.

Then, from the repository root:

```
./deploy.sh --dry-run    # list what would change, change nothing
./deploy.sh              # test, build, upload, migrate, cache
```

The script deploys the last **commit** of `master` (not uncommitted changes), installs
production dependencies locally, puts the site in maintenance mode, rsyncs, runs
`migrate --force` and `optimize`, and brings the site back up.
It never touches `.core/.env`, `.core/storage/`, `images/` or `media/` on the server.

## Backups

`php artisan database:backup` writes `storage/app/backup/backup-YYYY-MM-DD.gz`.
It is scheduled daily, but only runs if a cron job calls `php artisan schedule:run`.
