<?php

return[
    'tenant_model' => \MultiTenant\Models\Tenant::class,
    'database' => [
        'prefix' => env('DB_PREFIX', 'tenant_'),
        'connection' => env('DB_CONNECTION', 'mysql'),
        'template' => env('DB_DATABASE', 'laravel'),
    ],
    'reserved_subdomains' => [
        'www',
        'admin',
        'api',
        'auth',
        'dashboard',
        'app',
        'home',
        'blog',
        'forum',
        'support',
    ],
];