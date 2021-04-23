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
| 1. | Bearer Auth Module | Module | 1.0.0   | konekt.bearer_auth | Konekt\BearerAuth |
+----+--------------------+--------+---------+--------------------+-------------------+
```

## Add The Bearer Auth Middleware

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
