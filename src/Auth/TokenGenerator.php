<?php

declare(strict_types=1);

/**
 * Contains the TokenGenerator class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-02-03
 *
 */

namespace Konekt\BearerAuth\Auth;

use Carbon\CarbonImmutable;
use Illuminate\Contracts\Auth\Authenticatable;
use Lcobucci\JWT\Builder as JWTBuilder;
use Lcobucci\JWT\Token;

class TokenGenerator
{
    use HasTokenConfig;
    use KnowsDomainName;

    private JWTBuilder $jwtTokenBuilder;

    public function __construct()
    {
        $this->jwtTokenBuilder = $this->getTokenConfig()->builder();
    }

    public function generateAccessToken(Authenticatable $user): Token
    {
        return $this
            ->getCommonTokenParts($user, $this->getAccessTokenTtl())
            ->getToken($this->signer, $this->getKey())
            ;
    }

    public function getAccessTokenTtl(): int
    {
        return (int) config('konekt.bearer_auth.access_token.ttl');
    }

    public function generateRefreshToken(Authenticatable $user): Token
    {
        return $this
            ->getCommonTokenParts($user, config('konekt.bearer_auth.refresh_token.ttl'))
            ->withClaim('is_refresh', true)
            ->getToken($this->signer, $this->getKey())
            ;
    }

    private function getCommonTokenParts(Authenticatable $user, int $ttl): JWTBuilder
    {
        $now = CarbonImmutable::now();

        return $this->jwtTokenBuilder
            ->issuedAt($now)
            ->issuedBy($this->getDomainName())
            ->relatedTo((string)$user->getAuthIdentifier())
            ->permittedFor($this->getDomainName())
            ->expiresAt($now->addSeconds($ttl))
            ;
    }
}
