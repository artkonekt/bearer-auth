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
            'ttl' => env('BEARER_ACCESS_TOKEN_TTL', 90000 /* 25h */),
        ],
        'refresh_token' => [
            'ttl' => env('BEARER_REFRESH_TOKEN_TTL', 31708800 /* 367d */),
        ],
        'token_signature' => env('BEARER_JWT_TOKEN_SIGNATURE', env('APP_KEY')),
        'check' => [
            'user_is_active' => env('BEARER_CHECK_USER_IS_ACTIVE', true),
            'user_type' => env('BEARER_CHECK_USER_TYPE', true),
        ],
    ],
];
