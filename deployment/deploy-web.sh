#!/usr/bin/env bash

set -euo pipefail

# ============================================================
# Cultivation Web - Multi-client cPanel Deployment
#
# Production contract:
#
# APPPATH/
#   public/...              Laravel public_path()
#
# PUBLICPATH/
#   index.php
#   .htaccess
#   public/...              Browser /public/... URLs
#
# public/* is NEVER flattened into PUBLICPATH.
# ============================================================

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
WEBPUBLIC="$PUBLICPATH/public"

# ------------------------------------------------------------
# 2. Required commands
# ------------------------------------------------------------

for command_name in realpath rsync find sort xargs sha256sum; do
    command -v "$command_name" >/dev/null 2>&1 || {
        echo "ERROR: Required command missing: $command_name" >&2
        exit 1
    }
done

# ------------------------------------------------------------
# 3. Path validation
# ------------------------------------------------------------

for path in "$APPPATH" "$PUBLICPATH" "$PHP" "$COMPOSER_BIN"; do
    [[ "$path" = /* ]] || {
        echo "ERROR: Production paths must be absolute" >&2
        exit 1
    }
done

[[ "$APPPATH" != "/" && "$APPPATH" != "/home" ]] || exit 1
[[ "$PUBLICPATH" != "/" && "$PUBLICPATH" != "/home" ]] || exit 1

mkdir -p "$APPPATH"
mkdir -p "$PUBLICPATH"

# ------------------------------------------------------------
# 4. Preflight
# ------------------------------------------------------------

[[ -f "$APPPATH/.env" ]] || {
    echo "ERROR: Production .env missing: $APPPATH/.env" >&2
    exit 1
}

[[ -f "$REPOPATH/artisan" ]] || {
    echo "ERROR: artisan missing" >&2
    exit 1
}

[[ -f "$REPOPATH/composer.json" ]] || {
    echo "ERROR: composer.json missing" >&2
    exit 1
}

[[ -f "$REPOPATH/composer.lock" ]] || {
    echo "ERROR: composer.lock missing" >&2
    exit 1
}

[[ -d "$REPOPATH/public" ]] || {
    echo "ERROR: repository public directory missing" >&2
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
# 5. Replace application source
#
# Preserve:
#   APPPATH/.env
#   APPPATH/vendor
#   APPPATH/storage
#
# public is synchronized separately so runtime uploads survive.
# ------------------------------------------------------------

for directory in app config database resources routes; do
    rm -rf "$APPPATH/$directory"
    cp -R "$REPOPATH/$directory" "$APPPATH/"
done

# bootstrap/cache is runtime state, so copy bootstrap without
# destroying the existing runtime cache directory first.
mkdir -p "$APPPATH/bootstrap"

rsync -a \
    --exclude='/cache/' \
    "$REPOPATH/bootstrap/" \
    "$APPPATH/bootstrap/"

cp "$REPOPATH/artisan" "$APPPATH/artisan"
cp "$REPOPATH/composer.json" "$APPPATH/composer.json"
cp "$REPOPATH/composer.lock" "$APPPATH/composer.lock"

# ------------------------------------------------------------
# 6. Runtime directories
# ------------------------------------------------------------

mkdir -p "$APPPATH/public"
mkdir -p "$APPPATH/public/upload"

mkdir -p "$APPPATH/storage/app/public"
mkdir -p "$APPPATH/storage/framework/cache/data"
mkdir -p "$APPPATH/storage/framework/sessions"
mkdir -p "$APPPATH/storage/framework/testing"
mkdir -p "$APPPATH/storage/framework/views"
mkdir -p "$APPPATH/storage/logs"
mkdir -p "$APPPATH/bootstrap/cache"

# ------------------------------------------------------------
# 7. Runtime upload digest helper
# ------------------------------------------------------------

upload_digest() {
    local directory="$1"

    (
        cd "$directory"
        find . -type f -print0 \
            | LC_ALL=C sort -z \
            | xargs -0 -r sha256sum
    ) | sha256sum
}

APP_UPLOAD_BEFORE=$(upload_digest "$APPPATH/public/upload")

# ------------------------------------------------------------
# 8. Synchronize complete repository/public -> APPPATH/public
#
# This keeps Laravel public_path() correct.
# Runtime uploads are NEVER replaced.
# ------------------------------------------------------------

rsync -a \
    --exclude='/upload/' \
    --exclude='/storage/' \
    --exclude='/hot' \
    "$REPOPATH/public/" \
    "$APPPATH/public/"

APP_UPLOAD_AFTER=$(upload_digest "$APPPATH/public/upload")

[[ "$APP_UPLOAD_BEFORE" = "$APP_UPLOAD_AFTER" ]] || {
    echo "ERROR: APPPATH runtime uploads changed unexpectedly" >&2
    exit 1
}

[[ -f "$APPPATH/public/build/manifest.json" ]] || {
    echo "ERROR: APPPATH public build manifest missing" >&2
    exit 1
}

[[ -d "$APPPATH/public/cultivation" ]] || {
    echo "ERROR: APPPATH public cultivation directory missing" >&2
    exit 1
}

# ------------------------------------------------------------
# 9. Composer dependencies
# ------------------------------------------------------------

cd "$APPPATH"

"$COMPOSER_BIN" install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# ------------------------------------------------------------
# 10. Laravel deployment
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
# 11. Browser-facing COMPLETE public directory
#
# IMPORTANT:
#
# Repository/public/*
#        ↓
# PUBLICPATH/public/*
#
# Never:
# Never flatten child directories into the document root.
#
# Runtime browser uploads are preserved.
# ------------------------------------------------------------

mkdir -p "$WEBPUBLIC"
mkdir -p "$WEBPUBLIC/upload"

WEB_UPLOAD_BEFORE=$(upload_digest "$WEBPUBLIC/upload")

rsync -a \
    --exclude='/upload/' \
    --exclude='/storage/' \
    --exclude='/hot' \
    "$REPOPATH/public/" \
    "$WEBPUBLIC/"

WEB_UPLOAD_AFTER=$(upload_digest "$WEBPUBLIC/upload")

[[ "$WEB_UPLOAD_BEFORE" = "$WEB_UPLOAD_AFTER" ]] || {
    echo "ERROR: Browser runtime uploads changed unexpectedly" >&2
    exit 1
}

[[ -f "$WEBPUBLIC/build/manifest.json" ]] || {
    echo "ERROR: PUBLICPATH/public/build/manifest.json missing" >&2
    exit 1
}

[[ -d "$WEBPUBLIC/cultivation" ]] || {
    echo "ERROR: PUBLICPATH/public/cultivation missing" >&2
    exit 1
}

# ------------------------------------------------------------
# 12. Domain-root .htaccess
#
# index.php and .htaccess intentionally live at document root.
# All other public files remain under PUBLICPATH/public.
# ------------------------------------------------------------

[[ -f "$REPOPATH/public/.htaccess" ]] || {
    echo "ERROR: repository public/.htaccess missing" >&2
    exit 1
}

cp "$REPOPATH/public/.htaccess" "$PUBLICPATH/.htaccess"

# ------------------------------------------------------------
# 13. Generate production root index.php safely
#
# Do NOT write directly over the live index until PHP lint passes.
# ------------------------------------------------------------

INDEX_TMP="$PUBLICPATH/.index.php.deploying"

rm -f "$INDEX_TMP"

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
' "$APPPATH" "$INDEX_TMP"

"$PHP" -l "$INDEX_TMP" >/dev/null || {
    echo "ERROR: Generated production index.php has invalid PHP syntax" >&2
    rm -f "$INDEX_TMP"
    exit 1
}

mv -f "$INDEX_TMP" "$PUBLICPATH/index.php"

echo "Cultivation Web deployment completed successfully."