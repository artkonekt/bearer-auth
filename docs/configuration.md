# Configuration

The library can be configured with the following values:

| ENV Key                       | Meaning                                                                                               | Default             |
|:------------------------------|:------------------------------------------------------------------------------------------------------|:--------------------|
| `BEARER_ACCESS_TOKEN_TTL`     | Validity of the generated access tokens in seconds                                                    | 90000 (25 hours)    |
| `BEARER_REFRESH_TOKEN_TTL`    | Validity of the generated refresh tokens in seconds                                                   | 31708800 (367 days) |
| `BEARER_JWT_TOKEN_SIGNATURE`  | The SHA256 signing key for the token. Use a 64 character length random string                         | `env('APP_KEY')`    |
| `BEARER_AUTH_GUARD_NAME`      | Which Auth guard name to user for Laravel `Auth::xxx()` calls. Leave null for using the default guard | null                |
| `BEARER_CHECK_USER_IS_ACTIVE` | Whether to check if the Laravel user is active `Auth::user()->is_active`                              | true                |
| `BEARER_CHECK_USER_TYPE`      | Whether to check if the Laravel user type is API. `Auth::user()->type->isApi()`                       | true                |

## Configuring Without Env Vars

Since this library is a Concord Module, it is possible to directly set
the config values in `config/concord.php`. Since many of these values
are rather application than environment specific it makes sense to add
them application wide instead of "polluting" then `.env` file with these
never changing values.

### Setting The Guard Name

```php
// config/concord.php
return [
    'modules' => [
        //...
        Konekt\BearerAuth\Providers\ModuleServiceProvider::class => [
            'guard_name' => 'admin',
        ],
    ],
];
```

### Disable User Attribute Checks

```php
// config/concord.php
return [
    'modules' => [
        //...
        Konekt\BearerAuth\Providers\ModuleServiceProvider::class => [
            'check' => [
                'user_is_active' => false,
                'user_type' => false,
            ],
        ],
    ],
];
```

### Changing Token TTLs

To change the TTLs of the generated tokens, modify the
`access_token.ttl` and the `refresh_token.ttl` values respectively:

```php
// config/concord.php
return [
    'modules' => [
        //...
        Konekt\BearerAuth\Providers\ModuleServiceProvider::class => [
            'access_token' => [
                'ttl' => 3600 // 1 hour                
            ],
            'refresh_token' => [
                'ttl' => 604800 // 1 week                
            ],
        ],
    ],
];
```

## Built-in Routes

The module comes with two auth endpoints that are registered in the
routing table.

### Disabling Built-in Routes

To disable the built-in routes, set the routes config to `false` in
`config/concord.php`:

```php
return [
    'modules' => [
        //...
        Konekt\BearerAuth\Providers\ModuleServiceProvider::class => [
            'routes' => 'false',
        ],
    ],
];
```

If you disable the auth routes, then the auth/token endpoints need to
be implemented in your app. Feel free to copy the code from
`BearerAuthController` controller class from this libary to your
application.

### Change Route Parameters

> See also: [Concord Routes](https://konekt.dev/concord/1.9/routes)

To change parameters of those routes modify the module config:

```php
// config/concord.php
return [
    'modules' => [
        //...
        Konekt\BearerAuth\Providers\ModuleServiceProvider::class => [
            'routes' => [
                [
                    'files' => ['auth'],
                    'prefix' => '/your/custom-api-path-prefix/auth',
                    'as' => 'api.', // route name prefix
                    'middleware' => ['your-middleware-group'], // middleware group to apply to these routes 
                ],
            ],
        ],
    ],
];
```


---

**Next**: [Token Generation &raquo;](token-generation.md)
