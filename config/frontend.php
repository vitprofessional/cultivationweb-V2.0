<?php

return [
    // Default frontend layout. Can be overridden with FRONTEND_LAYOUT in .env
    'layout' => env('FRONTEND_LAYOUT', 'frontend.educavo-v2.page'),

    // Allowed layout aliases for runtime switch route.
    // Keep both aliases on one canonical layout so global header/footer stay identical.
    'layouts' => [
        'v1' => 'frontend.educavo-v2.page',
        'v2' => 'frontend.educavo-v2.page',
    ],
];
