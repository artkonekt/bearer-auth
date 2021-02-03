<?php

declare(strict_types=1);

/**
 * Contains the VerifiesToken trait.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-09-20
 *
 */

namespace Konekt\BearerAuth\Auth;

use Konekt\BearerAuth\Exceptions\ApiAuthorizationException;
use Lcobucci\JWT\Token;

trait VerifiesToken
{
    /** @var TokenVerifier */
    private TokenVerifier $tokenVerifier;

    /**
     * @throws ApiAuthorizationException
     */
    private function checkTokenSignature(Token $token): void
    {
        if (!$this->tokenVerifier->isSignatureValid($token)) {
            throw new ApiAuthorizationException(400, 'Invalid token signature');
        }
    }

    /**
     * @throws ApiAuthorizationException
     */
    private function checkIssuer(Token $token): void
    {
        if (!$this->tokenVerifier->wasIssuedForUs($token)) {
            throw new ApiAuthorizationException(401, 'The token was not issued for this server');
        }
    }

    /**
     * @throws ApiAuthorizationException
     */
    private function allowAuthTokenOnly(Token $token): void
    {
        if ($this->tokenVerifier->isRefreshToken($token)) {
            throw new ApiAuthorizationException(400, 'Refresh tokens cannot be used for authorization');
        }
    }

    /**
     * @throws ApiAuthorizationException
     */
    private function allowRefreshTokenOnly(Token $token): void
    {
        if (!$this->tokenVerifier->isRefreshToken($token)) {
            throw new ApiAuthorizationException(400, 'The passed token is not a refresh token');
        }
    }
}
