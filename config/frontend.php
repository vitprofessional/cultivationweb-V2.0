<?php

return [
    // Default frontend layout. Can be overridden with FRONTEND_LAYOUT in .env
    'layout' => env('FRONTEND_LAYOUT', 'frontend.educavo-v2.page'),

    // Allowed layout aliases for runtime switch route.
    'layouts' => [
        'v1' => 'frontend.include',
        'v2' => 'frontend.educavo-v2.page',
    ],
];
