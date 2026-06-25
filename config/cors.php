<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    | Specify which paths should have CORS headers applied
    */
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Methods
    |--------------------------------------------------------------------------
    | Allow all HTTP methods (GET, POST, PUT, DELETE, PATCH, OPTIONS)
    */
    'allowed_methods' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    | Specify which domains/ports can access your API
    | IMPORTANT: In production, replace with your actual domain!
    */
    'allowed_origins' => [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'http://localhost:3000',
        'http://127.0.0.1:3000',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins Patterns
    |--------------------------------------------------------------------------
    | Use regex patterns for dynamic origins
    */
    'allowed_origins_patterns' => [
        // Example: Allow all subdomains of yourdomain.com
        // '#^https://.*\.yourdomain\.com$#',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    | Allow all headers from client requests
    */
    'allowed_headers' => ['*'],

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    | Headers that browser allows JavaScript to access
    */
    'exposed_headers' => [
        'X-Total-Count',
        'X-Page-Count',
        'X-Per-Page',
        'X-Current-Page',
    ],

    /*
    |--------------------------------------------------------------------------
    | Max Age
    |--------------------------------------------------------------------------
    | How long (in seconds) browser can cache CORS response
    | 0 = Don't cache (safest for development)
    | 3600 = Cache for 1 hour (good for production)
    */
    'max_age' => 0,

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    | Allow cookies/authorization headers to be sent
    | MUST be true for Sanctum authentication to work
    */
    'supports_credentials' => true,
];
