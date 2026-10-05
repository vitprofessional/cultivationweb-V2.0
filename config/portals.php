<?php
// Explicit Admin origin; never derive portal links from Web APP_URL.
return [
    'base_url' => env('ADMIN_PORTAL_BASE_URL'),
    'admin' => env('ADMIN_LOGIN_URL'),
    'teacher' => env('TEACHER_LOGIN_URL'),
    'student' => env('STUDENT_LOGIN_URL'),
    'guardian' => env('GUARDIAN_LOGIN_URL'),
];
