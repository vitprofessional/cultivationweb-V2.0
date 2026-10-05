<?php
return [
    // Complete browser-facing static root. Local project-root hosting needs public/;
    // cPanel public_html hosting does not. No domain is embedded in source.
    'base_url' => env('PUBLIC_ASSET_BASE_URL') ?: env('ASSET_URL') ?: env('APP_URL'),
    'path_prefix' => env('PUBLIC_ASSET_PATH_PREFIX', env('APP_ENV') === 'local' ? 'public' : ''),
];
