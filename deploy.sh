#!/usr/bin/env bash
#
# Deploy ww2maps.net
#
#   ./deploy.sh              build, test, upload, migrate, cache
#   ./deploy.sh --dry-run    show what would change on the server, change nothing
#   ./deploy.sh --skip-tests skip the local test run
#   ./deploy.sh --any-branch allow deploying a branch other than DEPLOY_BRANCH
#
# What it does:
#   1. exports the committed code (git archive HEAD), so uncommitted or
#      ignored local files never reach the server
#   2. installs production dependencies there (composer install --no-dev)
#   3. puts the site in maintenance mode, rsyncs the build to the server,
#      runs migrations and rebuilds the config/route/view caches, site up again
#
# Never touched on the server: .core/.env, .core/storage/, images/, media/
#
# Needs locally: git, php 8.3+, composer (or .core/composer.phar), rsync, ssh
# Works with the bash 3.2 that ships with macOS.

set -euo pipefail

ROOT="$(cd "$(dirname "$0")" && pwd)"
cd "$ROOT"

DRY_RUN=0
SKIP_TESTS=0
ANY_BRANCH=0
for arg in "$@"; do
    case "$arg" in
        --dry-run)    DRY_RUN=1 ;;
        --skip-tests) SKIP_TESTS=1 ;;
        --any-branch) ANY_BRANCH=1 ;;
        -h|--help)    sed -n '2,20p' "$0"; exit 0 ;;
        *) echo "Unknown option: $arg" >&2; exit 1 ;;
    esac
done

say()  { printf '\n\033[1;34m==> %s\033[0m\n' "$*"; }
fail() { printf '\n\033[1;31mERROR: %s\033[0m\n' "$*" >&2; exit 1; }

# ---------------------------------------------------------------- settings
[ -f deploy.env ] || fail "deploy.env not found. Copy deploy.env.example to deploy.env and fill it in."
# shellcheck disable=SC1091
. ./deploy.env

: "${DEPLOY_HOST:?missing in deploy.env}"
: "${DEPLOY_USER:?missing in deploy.env}"
: "${DEPLOY_PATH:?missing in deploy.env}"
DEPLOY_PORT="${DEPLOY_PORT:-22}"
DEPLOY_PHP="${DEPLOY_PHP:-php}"
DEPLOY_BRANCH="${DEPLOY_BRANCH:-master}"

SSH_TARGET="$DEPLOY_USER@$DEPLOY_HOST"
SSH_OPTS="-p $DEPLOY_PORT -o ServerAliveInterval=30"

remote() {
    # shellcheck disable=SC2086
    ssh $SSH_OPTS "$SSH_TARGET" "$@"
}

artisan() {
    remote "cd '$DEPLOY_PATH/.core' && $DEPLOY_PHP artisan $*"
}

if command -v composer >/dev/null 2>&1; then
    COMPOSER="composer"
elif [ -f "$ROOT/.core/composer.phar" ]; then
    COMPOSER="php $ROOT/.core/composer.phar"
else
    fail "composer not found (install it, or put composer.phar in .core/)."
fi

for tool in git php rsync ssh; do
    command -v "$tool" >/dev/null 2>&1 || fail "$tool is not installed."
done

# ---------------------------------------------------------------- git checks
say "Checking the repository"
BRANCH="$(git rev-parse --abbrev-ref HEAD)"
COMMIT="$(git rev-parse --short HEAD)"

if [ "$BRANCH" != "$DEPLOY_BRANCH" ] && [ "$ANY_BRANCH" -eq 0 ]; then
    fail "You are on '$BRANCH', live deploys come from '$DEPLOY_BRANCH'. Use --any-branch to override."
fi

if [ -n "$(git status --porcelain --untracked-files=no)" ]; then
    echo "Note: you have uncommitted changes. Only the last commit ($COMMIT) is deployed."
fi

[ -f .core/composer.lock ] || fail ".core/composer.lock is missing. Run 'composer update' in .core and commit the lock file."

# ---------------------------------------------------------------- tests
if [ "$SKIP_TESTS" -eq 0 ]; then
    say "Running tests"
    [ -d .core/vendor ] || (cd .core && $COMPOSER install --no-interaction)
    (cd .core && php artisan test) || fail "Tests failed, nothing was deployed."
fi

# ---------------------------------------------------------------- build
BUILD="$(mktemp -d "${TMPDIR:-/tmp}/ww2web-build.XXXXXX")"
chmod 755 "$BUILD"   # rsync copies this folder's mode onto the web root
SITE_DOWN=0
cleanup() {
    rm -rf "$BUILD"
    if [ "$SITE_DOWN" -eq 1 ]; then
        printf '\n\033[1;31mThe deploy stopped while the site was in maintenance mode.\033[0m\n' >&2
        echo "Fix the problem and deploy again, or bring the site back with:" >&2
        echo "  ssh $SSH_OPTS $SSH_TARGET \"cd '$DEPLOY_PATH/.core' && $DEPLOY_PHP artisan up\"" >&2
    fi
}
trap cleanup EXIT

say "Building $BRANCH @ $COMMIT"
git archive HEAD | tar -x -C "$BUILD"

# The lock file must match what you tested; use the local one if it is not committed yet.
if [ ! -f "$BUILD/.core/composer.lock" ]; then
    cp .core/composer.lock "$BUILD/.core/composer.lock"
fi

(
    cd "$BUILD/.core"
    $COMPOSER install \
        --no-dev \
        --no-interaction \
        --no-progress \
        --prefer-dist \
        --optimize-autoloader \
        --classmap-authoritative \
        --no-scripts
)

# Not needed on the live site
rm -rf \
    "$BUILD/.core/tests" \
    "$BUILD/.core/phpunit.xml" \
    "$BUILD/.core/composer.phar" \
    "$BUILD/.core/.env.example" \
    "$BUILD/deploy.sh" \
    "$BUILD/deploy.env.example" \
    "$BUILD/README.md" \
    "$BUILD/.gitignore" \
    "$BUILD/.core/.gitattributes"

echo "$COMMIT" > "$BUILD/.core/REVISION"

# ---------------------------------------------------------------- upload
RSYNC_ARGS=(
    -rlptz --delete
    --exclude=/.core/.env
    --exclude=/.core/storage/
    --exclude=/images/
    --exclude=/media/
    --exclude=/img/
    --exclude=/deploy.env
    --exclude=/.well-known/
    --exclude=.DS_Store
    -e "ssh $SSH_OPTS"
)

say "Checking the server"
# --delete removes everything in DEPLOY_PATH that is not in the build, so make
# sure DEPLOY_PATH really is the site and not, say, the home folder.
remote "test -f '$DEPLOY_PATH/index.php' && test -d '$DEPLOY_PATH/.core'" \
    || fail "$DEPLOY_PATH does not look like the ww2maps web root (no index.php and .core/). Check DEPLOY_PATH."
remote "test -f '$DEPLOY_PATH/.core/.env'" \
    || fail "$DEPLOY_PATH/.core/.env does not exist on the server. Create it first (see README)."
remote "$DEPLOY_PHP -r 'exit(version_compare(PHP_VERSION, \"8.3.0\", \">=\") ? 0 : 1);'" \
    || fail "'$DEPLOY_PHP' on the server is older than PHP 8.3. Set DEPLOY_PHP in deploy.env."

if [ "$DRY_RUN" -eq 1 ]; then
    say "Dry run: files that would change on $DEPLOY_HOST"
    rsync "${RSYNC_ARGS[@]}" -n -v "$BUILD/" "$SSH_TARGET:$DEPLOY_PATH/"
    say "Dry run finished, nothing was changed."
    exit 0
fi

say "Maintenance mode on"
# May fail on the very first deploy (old code on the server); that is fine.
artisan down --retry=30 || true
SITE_DOWN=1

say "Uploading to $DEPLOY_HOST:$DEPLOY_PATH"
rsync "${RSYNC_ARGS[@]}" "$BUILD/" "$SSH_TARGET:$DEPLOY_PATH/"

say "Preparing storage folders"
remote "cd '$DEPLOY_PATH/.core' && mkdir -p storage/app/backup storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache"

say "Migrating and caching"
artisan package:discover
artisan migrate --force
artisan optimize

say "Maintenance mode off"
artisan up
SITE_DOWN=0

say "Deployed $BRANCH @ $COMMIT to https://ww2maps.net"
