<?php

declare(strict_types=1);

/**
 * Contains the AuthorizesApiUser trait.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-09-20
 *
 */

namespace Konekt\BearerAuth\Auth;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Konekt\BearerAuth\Exceptions\ApiAuthorizationException;

trait AuthorizesApiUser
{
    /**
     * @throws ApiAuthorizationException
     */
    private function authenticateUser($user): void
    {
        if (!$this->bearerAuth()->onceUsingId($user)) {
            throw new ApiAuthorizationException(401, 'You don\'t exist in our records');
        }
    }

    /**
     * @throws ApiAuthorizationException
     */
    private function checkIfUserIsActive(): void
    {
        if (!config('konekt.bearer_auth.check.user_is_active')) {
            return;
        }

        if (!$this->bearerAuth()->user()->is_active) {
            throw new ApiAuthorizationException(403, 'You are no longer an active user here');
        }
    }

    /**
     * @throws ApiAuthorizationException
     */
    private function checkIfUserApiAccessIsAllowed(): void
    {
        if (!config('konekt.bearer_auth.check.user_type')) {
            return;
        }

        if (!$this->bearerAuth()->user()->type->isApi()) {
            throw new ApiAuthorizationException(403, 'You are no longer an API user');
        }
    }

    private function bearerAuth(): Guard
    {
        return Auth::guard(config('konekt.bearer_auth.guard_name'));
    }
}
