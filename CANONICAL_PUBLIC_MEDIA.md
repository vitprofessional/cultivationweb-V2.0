# Canonical public media URLs

## Authority and scope

Admin writes tenant media to its existing public upload directories. Both applications read the same tenant database. Web renders Admin-hosted media over HTTP(S); a shared filesystem, file copies, new media records and object storage are not required.

Only the legacy Admin Slider preview and Web homepage Slider use this contract today. Logo, Principal/Chairman, Teacher/Staff, Gallery and other consumers remain on their existing paths and are deferred. Static CSS/JS/demo images retain their existing asset() semantics.

## Per-application configuration

Admin APP_URL identifies the Admin application. Web APP_URL identifies the public website. Do not copy the entire Admin .env to Web. Tenant DB connection settings may be shared, but application identity/asset settings are separate. Never publish credentials.

- PUBLIC_MEDIA_BASE_URL: HTTP(S) Admin media authority. Admin defaults to its own APP_URL when unset/empty; Web requires an explicit nonempty value and never falls back to Web APP_URL.
- PUBLIC_MEDIA_PATH_PREFIX: public by default, preserving existing project-root /public/upload URLs. Set to an empty string only when the selected base already directly serves the Laravel public directory. A base ending in /public is also accepted without duplicating that boundary.
- ASSET_URL: unchanged static/compiled-asset authority; not used by the canonical Slider resolver.

Synthetic example (not tenant defaults):

    Admin APP_URL=https://admin.school.example.test
    Admin PUBLIC_MEDIA_BASE_URL=
    Web APP_URL=https://school.example.test
    Web PUBLIC_MEDIA_BASE_URL=https://admin.school.example.test
    Both PUBLIC_MEDIA_PATH_PREFIX=public

Local Web setup must likewise explicitly name the local Admin application URL as PUBLIC_MEDIA_BASE_URL. No real .env is edited by this change. Verify the actual deployed document-root mapping before selecting the prefix or enabling the setting. Configuration changes and deployment remain a separate operator action.

## Database and URL contract

home_sliders.avatar remains a filename such as a UUID .jpg. App\\Services\\PublicMediaUrl::slider() supplies the existing upload/image/webHomepage category and returns a nullable canonical URL. url() accepts a public-root-relative path, normalizes slash boundaries and URL-encodes individual segments.

Only the known public boundary is coalesced. Interior public segments are preserved. Null/empty/unsafe values yield null. Traversal, filesystem syntax, control characters, pre-encoded percent sequences, queries and fragments are rejected. Credentials/query/fragment in the configured base are rejected. No full-URL DB passthrough was added: the audited Slider stores filenames only. Broader Gallery URL tolerance is not implicitly imported into this contract.

The service performs no filesystem operations or remote HTTP requests. It does not test Web-local file existence: Web is not the media writer. Missing/invalid Web configuration, or no valid Slider references, leaves the existing demo fallback. A syntactically valid URL is not proof that its remote image exists; a missing remote asset may remain a broken slide until the deployment/content issue is corrected. No runtime HTTP probing, SSRF surface or remote-content fallback policy is introduced.

The current newest-five query, ordering, captions, CTA, layout and carousel options are preserved. No migration/path rewrite, upload/delete change or media copy is included.

## Config cache and tests

env() is used only in config/media.php. Runtime consumers use Laravel configuration. Rebuild configuration cache using the existing approved deployment process after changing environment values; this phase does not deploy or clear operational caches.

Focused coverage: PublicMediaUrlTest (both repositories); SliderMediaPreviewTest (Admin); SliderMediaRenderTest (Web). Database-backed tests require existing isolated cultivation_test schema and transaction rollback. They must not migrate or use operational data merely to verify URLs. Web PublicSubmissionSecurityTest remains a required regression; the historical full Web suite is not claimed.

## Future Slider CRUD and remaining risk

Future uploads retain the same filename/category/storage authority. Authorize and validate, write a unique new file, persist its DB reference, and only then remove an old asset if containment, ownership and absence of other references are proven. Legacy deletion's unsafe concatenation/delete-before-save and the legacy raw headline alt attribute remain deferred. This URL-only service must never be treated as a safe deletion-path resolver.

Deployment must validate Admin write access, public image readability/HTTPS and correct image MIME types. No production reachability/permission claim is made by URL tests. Other media consumers still need separate approved migration to this helper.
