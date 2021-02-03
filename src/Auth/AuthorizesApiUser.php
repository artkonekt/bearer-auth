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

use Konekt\BearerAuth\Exceptions\ApiAuthorizationException;
use Illuminate\Support\Facades\Auth;

trait AuthorizesApiUser
{
    /**
     * @throws ApiAuthorizationException
     */
    private function authenticateUser($user): void
    {
        if (!Auth::onceUsingId($user)) {
            throw new ApiAuthorizationException(401, 'You don\'t exist in our records');
        }
    }

    /**
     * @throws ApiAuthorizationException
     */
    private function checkIfUserIsActive(): void
    {
        if (!Auth::user()->is_active) {
            throw new ApiAuthorizationException(403, 'You are no longer an active user here');
        }
    }

    /**
     * @throws ApiAuthorizationException
     */
    private function checkIfUserApiAccessIsAllowed(): void
    {
        if (!Auth::user()->type->isApi()) {
            throw new ApiAuthorizationException(403, 'You are no longer an API user');
        }
    }
}
