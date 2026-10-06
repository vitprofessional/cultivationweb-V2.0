#!/usr/bin/env bash

set -euo pipefail

# ------------------------------------------------------------
# 1. Repository + operator-owned client configuration
# ------------------------------------------------------------

REPOPATH=$(realpath -e "$(dirname "$0")/..")

CONFIG_FILE=${CULTIVATION_WEB_DEPLOY_CONFIG:-"${HOME:?}/.config/cultivation/web-deploy.env"}

[[ -f "$CONFIG_FILE" && ! -L "$CONFIG_FILE" ]] || {
    echo "ERROR: Missing or invalid deployment config: $CONFIG_FILE" >&2
    exit 1
}

CONFIG_FILE=$(realpath -e "$CONFIG_FILE")

source "$CONFIG_FILE"

: "${CULTIVATION_WEB_APP_PATH:?Missing CULTIVATION_WEB_APP_PATH}"
: "${CULTIVATION_WEB_PUBLIC_PATH:?Missing CULTIVATION_WEB_PUBLIC_PATH}"
: "${CULTIVATION_WEB_PHP:?Missing CULTIVATION_WEB_PHP}"
: "${CULTIVATION_WEB_COMPOSER:?Missing CULTIVATION_WEB_COMPOSER}"

APPPATH="$CULTIVATION_WEB_APP_PATH"
PUBLICPATH="$CULTIVATION_WEB_PUBLIC_PATH"
PHP="$CULTIVATION_WEB_PHP"
COMPOSER_BIN="$CULTIVATION_WEB_COMPOSER"

# ------------------------------------------------------------
# 2. Required commands + path validation
# ------------------------------------------------------------

for command_name in realpath rsync find sort xargs sha256sum; do
    command -v "$command_name" >/dev/null 2>&1 || {
        echo "ERROR: Required command missing: $command_name" >&2
        exit 1
    }
done

for path in "$APPPATH" "$PUBLICPATH" "$PHP" "$COMPOSER_BIN"; do
    [[ "$path" = /* ]] || {
        echo "ERROR: Production paths must be absolute" >&2
        exit 1
    }
done

mkdir -p "$APPPATH"
mkdir -p "$PUBLICPATH"

[[ -f "$APPPATH/.env" ]] || {
    echo "ERROR: Production .env missing: $APPPATH/.env" >&2
    exit 1
}

[[ -f "$REPOPATH/artisan" ]] || {
    echo "ERROR: artisan missing from repository" >&2
    exit 1
}

[[ -f "$REPOPATH/composer.json" ]] || {
    echo "ERROR: composer.json missing from repository" >&2
    exit 1
}

[[ -f "$REPOPATH/composer.lock" ]] || {
    echo "ERROR: composer.lock missing from repository" >&2
    exit 1
}

[[ -f "$REPOPATH/public/build/manifest.json" ]] || {
    echo "ERROR: Vite production build missing" >&2
    exit 1
}

[[ -d "$REPOPATH/public/cultivation" ]] || {
    echo "ERROR: Cultivation public assets missing" >&2
    exit 1
}

[[ -x "$PHP" ]] || {
    echo "ERROR: PHP executable unavailable" >&2
    exit 1
}

[[ -x "$COMPOSER_BIN" ]] || {
    echo "ERROR: Composer executable unavailable" >&2
    exit 1
}

# ------------------------------------------------------------
# 3. Replace Laravel application source
#
# Persistent:
#   APPPATH/.env
#   APPPATH/vendor
#   APPPATH/storage
# ------------------------------------------------------------

rm -rf "$APPPATH/app"
rm -rf "$APPPATH/bootstrap"
rm -rf "$APPPATH/config"
rm -rf "$APPPATH/database"
rm -rf "$APPPATH/public"
rm -rf "$APPPATH/resources"
rm -rf "$APPPATH/routes"

cp -R "$REPOPATH/app" "$APPPATH/"
cp -R "$REPOPATH/bootstrap" "$APPPATH/"
cp -R "$REPOPATH/config" "$APPPATH/"
cp -R "$REPOPATH/database" "$APPPATH/"
cp -R "$REPOPATH/public" "$APPPATH/"
cp -R "$REPOPATH/resources" "$APPPATH/"
cp -R "$REPOPATH/routes" "$APPPATH/"

cp "$REPOPATH/artisan" "$APPPATH/artisan"
cp "$REPOPATH/composer.json" "$APPPATH/composer.json"
cp "$REPOPATH/composer.lock" "$APPPATH/composer.lock"

# ------------------------------------------------------------
# 4. Laravel runtime directories
# ------------------------------------------------------------

mkdir -p "$APPPATH/storage/app/public"
mkdir -p "$APPPATH/storage/framework/cache/data"
mkdir -p "$APPPATH/storage/framework/sessions"
mkdir -p "$APPPATH/storage/framework/testing"
mkdir -p "$APPPATH/storage/framework/views"
mkdir -p "$APPPATH/storage/logs"
mkdir -p "$APPPATH/bootstrap/cache"

# ------------------------------------------------------------
# 5. Composer
# ------------------------------------------------------------

cd "$APPPATH"

"$COMPOSER_BIN" install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# ------------------------------------------------------------
# 6. Laravel
# ------------------------------------------------------------

"$PHP" artisan config:clear
"$PHP" artisan route:clear
"$PHP" artisan view:clear

"$PHP" artisan migrate --force

"$PHP" artisan cache:clear
"$PHP" artisan config:cache
"$PHP" artisan route:cache
"$PHP" artisan view:cache

# ------------------------------------------------------------
# 7. Publish complete public folder
#
# Preserve the existing URL convention:
#   /public/cultivation/...
#   /public/img/...
#   /public/upload/...
# ------------------------------------------------------------

mkdir -p "$PUBLICPATH/public"
mkdir -p "$PUBLICPATH/public/upload"

# Preserve runtime uploads.
upload_digest() {
    (
        cd "$PUBLICPATH/public/upload"
        find . -type f -print0 \
            | LC_ALL=C sort -z \
            | xargs -0 -r sha256sum
    ) | sha256sum
}

before_uploads=$(upload_digest)

rsync -a \
    --exclude='/upload/' \
    --exclude='/storage/' \
    --exclude='/hot' \
    "$REPOPATH/public/" \
    "$PUBLICPATH/public/"


after_uploads=$(upload_digest)

# Existing production uploads must not be changed by a normal deployment.
# A fresh installation may legitimately receive repository seed uploads.
if [[ "$before_uploads" != "$after_uploads" ]] \
    && [[ -n "$before_uploads" ]]; then
    echo "ERROR: Runtime upload state changed unexpectedly" >&2
    exit 1
fi

# ------------------------------------------------------------
# 8. Domain-root Apache files
# ------------------------------------------------------------

if [[ -f "$REPOPATH/public/.htaccess" ]]; then
    cp "$REPOPATH/public/.htaccess" "$PUBLICPATH/.htaccess"
fi

if [[ -f "$REPOPATH/public/robots.txt" ]]; then
    cp "$REPOPATH/public/robots.txt" "$PUBLICPATH/robots.txt"
fi

if [[ -f "$REPOPATH/public/favicon.ico" ]]; then
    cp "$REPOPATH/public/favicon.ico" "$PUBLICPATH/favicon.ico"
fi

# ------------------------------------------------------------
# 9. Generate production entry point last
# ------------------------------------------------------------

"$PHP" -r '
$appPath = $argv[1];
$target = $argv[2];

$content = "<?php\n\n"
    ."use Illuminate\\Foundation\\Application;\n"
    ."use Illuminate\\Http\\Request;\n\n"
    ."define(\"LARAVEL_START\", microtime(true));\n\n"
    ."\$applicationPath = ".var_export($appPath, true).";\n\n"
    ."if (file_exists(\$maintenance = \$applicationPath.\"/storage/framework/maintenance.php\")) {\n"
    ."    require \$maintenance;\n"
    ."}\n\n"
    ."require \$applicationPath.\"/vendor/autoload.php\";\n\n"
    ."/** @var Application \$app */\n"
    ."\$app = require_once \$applicationPath.\"/bootstrap/app.php\";\n\n"
    ."\$app->handleRequest(Request::capture());\n";

file_put_contents($target, $content);
' "$APPPATH" "$PUBLICPATH/index.php"

echo "Cultivation Web deployment completed successfully."