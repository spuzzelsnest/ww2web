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

## Photo thumbnails

The map page shows 480 / 960 px WebP copies of the photos (`images/thumbs/`),
so phones do not download the multi-megabyte originals. The full picture is only
loaded when someone opens the full-screen viewer.

```
php artisan images:thumbs           # make the missing ones
php artisan images:thumbs --force   # rebuild all
```

New uploads from the admin page get their thumbnails straight away, and
`deploy.sh` runs the command on the server after every deploy. Pictures without
thumbnails simply fall back to the original.

## Then & Now photos

The map opens in **Then & Now** mode. A photo that has a picture of the same
spot today shows a slider: drag sideways to compare.

1. Name the "now" picture like the original with `_n` added:
   `images/aachen01.jpg` → `images/aachen01_n.jpg`
2. Put it in `images/` (on the server: upload it there, e.g. with SFTP).
3. Run `php artisan footage:link-now` (deploy.sh does this too). It fills
   `footages.now_name` for every `_n` picture it finds, clears it when a
   picture was removed, and warns about `_n` files without a matching footage.
   `--dry-run` shows what would change.
4. Run `php artisan images:thumbs` for the small copies (deploy.sh does this too).

Optional, straight in the database: `now_taken_on` (date) and `now_credit`
(photographer) are shown under the slider.

Footage with a "now" photo glows on the map.

## Guided tours

The **Tours** switch shows the published tours. A tour is an ordered list of
footage ("stops"); each stop can name the military unit seen there, so one tour
can follow several units (`units` table).

| table        | what                                                               |
|--------------|--------------------------------------------------------------------|
| `tours`      | `slug`, `title`, `intro`, `operationId`, `published`, `sort`        |
| `tour_stops` | `tour_id`, `footage_id`, `position` (1, 2, 3 …), `unit_id`, and optional `title` / `text` that replace the footage's own |
| `units`      | `name` ("506th PIR"), `full_name`, `side` ("allied" / "axis")      |

Example: `php artisan db:seed --class=ExampleTourSeeder` adds "Holding Bastogne"
(8 stops, 4 units). Running it again updates it instead of adding a copy.

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
