<?php

return [
    /*
    |--------------------------------------------------------------------------
    | API Logging Strategy
    |--------------------------------------------------------------------------
    |
    | Supported strategies: "file", "database"
    |
    */
    'api_strategy' => env('API_LOG_STRATEGY', 'file'),

    /*
    |--------------------------------------------------------------------------
    | File Logger Settings
    |--------------------------------------------------------------------------
    */
    'file' => [
        'path'        => storage_path('logs/api.log'),
        'max_size'    => env('API_LOG_MAX_SIZE', 10 * 1024 * 1024), // 10MB
        'permissions' => 0644,
    ],

    /*
    |--------------------------------------------------------------------------
    | Database Logger Settings
    |--------------------------------------------------------------------------
    */
    'database' => [
        'table'      => env('API_LOG_TABLE', 'api_logs'),
        'connection' => env('API_LOG_CONNECTION', 'mysql'),
    ],
];
