#!/usr/bin/env bash
set -euo pipefail

# Publish code-controlled assets only. Runtime uploads remain in APP/public.
REPOPATH=$(realpath -e "${1:?repository path required}")
APPPATH=$(realpath -e "${2:?application path required}")
PUBLICPATH=$(realpath -e "${3:?public document root required}")
for path in "$REPOPATH" "$APPPATH" "$PUBLICPATH"; do
    [[ "$path" = /* && "$path" != / && "$path" != /home ]] || { echo 'Unsafe deployment path' >&2; exit 1; }
done
[[ "$APPPATH" != "$REPOPATH" && "$PUBLICPATH" != "$REPOPATH" && "$PUBLICPATH" != "$APPPATH" && "$PUBLICPATH" != "$APPPATH/public" ]] || exit 1
[[ -f "$APPPATH/.env" && -f "$REPOPATH/public/build/manifest.json" ]] || exit 1
[[ ! -L "$APPPATH/public" && ! -L "$APPPATH/public/upload" ]] || { echo 'Audit application public/upload ownership first' >&2; exit 1; }

# Check every public mapping before copying anything. Never replace a real directory
# or a differently owned symlink (especially an existing upload directory).
roots=(assets back-office build cultivation fonts img lightbox music owl-carousel upload)
for name in "${roots[@]}" public; do
    target="$APPPATH/public/$name"; [[ "$name" = public ]] && target="$APPPATH/public"
    destination="$PUBLICPATH/$name"
    if [[ -e "$destination" || -L "$destination" ]]; then
        [[ -L "$destination" && "$(readlink -m "$destination")" = "$target" ]] || {
            echo "Existing public mapping requires manual reconciliation: $name. Nothing removed." >&2; exit 1;
        }
    fi
done
mkdir -p "$APPPATH/public/upload"

# Source inventory is Git, not an unrestricted recursive copy of public/.
# Uploaded media, storage links, PHP entry points and QA images are never seeded.
while IFS= read -r -d '' source; do
    relative="${source#public/}"
    case "$relative" in upload/*|storage/*|index.php|.htaccess|*-final-*.png|before-*.png|after-*.png|final-*.png|hero-header-*.png) continue ;; esac
    [[ -f "$REPOPATH/$source" && ! -L "$REPOPATH/$source" ]] || continue
    [[ "$relative" != *../* && "$relative" != /* ]] || exit 1
    destination="$APPPATH/public/$relative"
    part="$(dirname "$destination")"
    while [[ "$part" != "$APPPATH" ]]; do
        [[ ! -L "$part" ]] || { echo 'Static destination contains a symlink' >&2; exit 1; }
        part="$(dirname "$part")"
    done
    [[ ! -L "$destination" ]] || exit 1
    mkdir -p "$(dirname "$destination")"
    cp -- "$REPOPATH/$source" "$destination"
done < <(git -C "$REPOPATH" ls-files -z -- public)

for name in "${roots[@]}"; do
    [[ -d "$APPPATH/public/$name" ]] || continue
    [[ -L "$PUBLICPATH/$name" ]] || ln -s "$APPPATH/public/$name" "$PUBLICPATH/$name"
done
# Compatibility for old /public/... bookmarks. New URLs use the document root.
[[ -L "$PUBLICPATH/public" ]] || ln -s "$APPPATH/public" "$PUBLICPATH/public"
for name in avatar.png avatar.jpeg favicon.ico logo.png logoWhite.png icon.jpg robots.txt; do
    [[ ! -f "$APPPATH/public/$name" ]] || cp -- "$APPPATH/public/$name" "$PUBLICPATH/$name"
done
echo 'Static assets published; runtime uploads were not copied, replaced or deleted.'
