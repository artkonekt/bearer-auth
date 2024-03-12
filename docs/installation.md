# Installation

```bash
composer require konekt/bearer-auth
touch config/concord.php
```

Edit `config/concord.php` and add this content to it:

```php
<?php

return [
    'modules' => [
        Konekt\BearerAuth\Providers\ModuleServiceProvider::class,
    ]
];
```

Test if all worked well by invoking the command:

```bash
php artisan concord:modules
```

Now you should see this:

```
+----+--------------------+--------+---------+--------------------+-------------------+
| #  | Name               | Kind   | Version | Id                 | Namespace         |
+----+--------------------+--------+---------+--------------------+-------------------+
| 1. | Bearer Auth Module | Module | 1.5.0   | konekt.bearer_auth | Konekt\BearerAuth |
+----+--------------------+--------+---------+--------------------+-------------------+
```

## Add The Bearer Auth Middleware

To protect your API routes with the Bearer Authentication, you need to
use the `BearerTokenRequired` middleware class in the given middleware
group.

```php
namespace App\Http;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        // ...
        'api' => [
            'throttle:60,1',
             \Konekt\BearerAuth\Http\Middleware\BearerTokenRequired::class
        ],
    ];
//...
}
```

> You can use other names than `api` for the middleware group

Afterwards, add the middleware group to your routes:

```php
Route::middleware(['api'])->group(function () {
    // Add your API endpoints here
});
```

If you're using the `api` middleware group and the default Laravel setup,
then you can simply add your routes to the `routes/api.php` file since
the middleware group is already assigned in the
`app/Providers/RouteServiceProvider.php` file.

## Add The Token Endpoints

If you use the default token routes provided by the package, add the
`api-auth` middleware group to your application's HTTP Kernel:

```php
namespace App\Http;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        // ...
        'api-auth' => [
            'throttle:9,1',
        ],
    ];
//...
}
```

> **Optional**: Protect the token endpoints with more strict throttling, eg `throttle:9,1`

## Add Exception Handling

---

**Next**: [Configuration &raquo;](configuration.md)
