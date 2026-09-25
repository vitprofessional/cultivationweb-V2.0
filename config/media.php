<?php

return [
    // Tenant media must use the Admin authority, never this website's APP_URL.
    'public_base_url' => env('PUBLIC_MEDIA_BASE_URL'),
    // Empty only when the configured base already serves the public directory.
    'public_path_prefix' => env('PUBLIC_MEDIA_PATH_PREFIX', 'public'),
];
