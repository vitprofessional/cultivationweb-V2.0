#!/usr/bin/env bash
set -euo pipefail
# Isolated filesystem fixture, never a real application deployment.
publisher="$(realpath "$(dirname "$0")/../../deployment/publish-public.sh")"
fixture="$(mktemp -d)"
repo="$fixture/repository"; app="$fixture/application"; web="$fixture/document-root"
mkdir -p "$repo/public/build" "$repo/public/upload" "$app/public/upload" "$web"
touch "$app/.env"
printf '{}' > "$repo/public/build/manifest.json"
printf 'version one' > "$repo/public/build/fixture.js"
printf 'source upload must not replace runtime' > "$repo/public/upload/owned.jpg"
printf 'runtime upload' > "$app/public/upload/owned.jpg"
# Mock read-only tracked inventory. No Git index is created or staged.
git() { printf 'public/build/manifest.json\0public/build/fixture.js\0public/upload/owned.jpg\0'; }
export -f git
before="$(sha256sum "$app/public/upload/owned.jpg")"
bash "$publisher" "$repo" "$app" "$web"
[[ -L "$web/upload" && -L "$web/build" ]]
[[ "$(cat "$web/upload/owned.jpg")" = 'runtime upload' ]]
printf 'version two' > "$repo/public/build/fixture.js"
bash "$publisher" "$repo" "$app" "$web"
[[ "$(cat "$web/build/fixture.js")" = 'version two' ]]
[[ "$(sha256sum "$app/public/upload/owned.jpg")" = "$before" ]]
mkdir -p "$fixture/conflicting-document-root/upload"
printf 'existing public upload' > "$fixture/conflicting-document-root/upload/owned.jpg"
if bash "$publisher" "$repo" "$app" "$fixture/conflicting-document-root"; then
    echo 'Expected conflicting upload mapping to stop' >&2; exit 1
fi
[[ "$(cat "$fixture/conflicting-document-root/upload/owned.jpg")" = 'existing public upload' ]]
echo "PASS: repeated publish preserved runtime checksum; conflicting mapping preserved. Fixture: $fixture"
