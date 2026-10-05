# Public roots and runtime uploads

No deployment or production environment change is authorized by this document.

## URL contract

`PUBLIC_MEDIA_BASE_URL` is the complete browser-facing **Admin public root**.
`PUBLIC_MEDIA_PATH_PREFIX` defaults to empty. Set `public` explicitly only for a
legacy origin that exposes that required prefix. Never store an origin in the DB.
Existing filename-only authorities remain supported and resolve within their
known upload folder; known relative About paths remain supported without backfill.

- Local project-root hosting: base `http://localhost/cultivation/public`, empty
  prefix → `/cultivation/public/upload/image/cultivation/about.jpg`.
- cPanel document-root hosting: base `https://admin.example.test`, empty prefix
  → `/upload/image/cultivation/about.jpg` (no `/public/`).
- Slider: `upload/image/webHomepage/<filename>`.
- Gallery: `upload/image/PhotoGallery/<filename>`.

Web static assets are separate: `PUBLIC_ASSET_BASE_URL` (or `ASSET_URL`/`APP_URL`)
and `PUBLIC_ASSET_PATH_PREFIX`. Local defaults to `public`; cPanel defaults to
empty. Vite uses the same static resolver, including `build/assets/*`.
No domains or localhost values are embedded in application code.

## cPanel mapping / preservation

`publish-public.sh REPOPATH APPPATH PUBLICPATH` publishes **tracked static files**
into APPPATH/public, excluding uploads and storage. PUBLICPATH/build and other
static directories map to those same directories. PUBLICPATH/upload maps to
APPPATH/public/upload. A compatibility PUBLICPATH/public link keeps old URLs
reachable. Neither application public/ nor runtime upload/ is ever replaced.
Repeated publishing never seeds repository upload files over runtime files.

Before first activation, an operator must:

1. Back up and inventory both APPPATH/public/upload and existing
   PUBLICPATH/public/upload (and any root upload directory); compare ownership
   and hashes. Existing production uploads may currently exist only in the latter.
2. Reconcile conflicting physical directories **with explicit approval**, retaining
   every runtime upload. The script deliberately stops instead of replacing them.
3. Confirm Apache allows same-owner symlinks and denies executable scripts in
   upload folders. Retain existing upload-specific access rules.
4. Configure Admin media and Web asset roots for the actual tenant domains.
5. Verify image/video/document HTTP responses and build-manifest assets from the
   browser, then repeat deployment in staging and compare upload checksums.

The Web publish script can also be used for Admin public assets with explicitly
verified Admin paths; no Admin cPanel pipeline existed in this repository audit.
Do not point Web PUBLIC_MEDIA_BASE_URL at Web-local uploads.
Production migration is a separate approval gate, not an automatic deploy task.

## Verification boundary

The isolated preservation smoke test is
`tests/Browser/public-assets-preservation.sh`. It creates temporary directories
only and checks repeat-publish upload hashes and conflicting-mapping refusal.
Run it on the intended Linux staging host before activation. Windows local QA
could not execute its symlink step (`Operation not permitted`), even with elevated
tool execution; this is not evidence that production symlink mapping works.
