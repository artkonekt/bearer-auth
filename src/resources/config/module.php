<?php

return [
    'routes' => [
        [
            'files' => ['auth'],
            'prefix' => '/api/1.0/auth',
            'as' => 'api.',
            'middleware' => ['apiauth'],
        ],
    ],
    'auth' => [
        'access_token' => [
            'ttl' => env('API_ACCESS_TOKEN_TTL', 90000 /* 25h */),
        ],
        'refresh_token' => [
            'ttl' => env('API_REFRESH_TOKEN_TTL', 31708800 /* 367d */),
        ],
        'token_signature' => env('API_JWT_TOKEN_SIGNATURE', env('APP_KEY')),
    ],
];
