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

---

**Next**: [Token Generation &raquo;](token-generation.md)
