<?php

namespace Konekt\BearerAuth\Auth;

use App\User;
use Lcobucci\JWT\Builder as JWTBuilder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;

class TokenGenerator
{
    use HasTokenSigner, KnowsDomainName;

    /** @var JWTBuilder */
    private $jwtTokenBuilder;

    public function __construct(JWTBuilder $jwtTokenBuilder, Sha256 $signer)
    {
        $this->jwtTokenBuilder = $jwtTokenBuilder;
        $this->signer = $signer;
    }

    public function generateAccessToken(User $user): Token
    {
        return $this
            ->getCommonTokenParts($user, $this->getAccessTokenTtl())
            ->getToken($this->signer, $this->getKey())
            ;
    }

    public function getAccessTokenTtl(): int
    {
        return (int) config('api.auth.access_token.ttl');
    }

    public function generateRefreshToken(User $user): Token
    {
        return $this
            ->getCommonTokenParts($user, config('api.auth.refresh_token.ttl'))
            ->withClaim('is_refresh', true)
            ->getToken($this->signer, $this->getKey())
            ;
    }

    private function getCommonTokenParts(User $user, int $ttl): JWTBuilder
    {
        $now = time();

        return $this->jwtTokenBuilder
            ->issuedAt($now)
            ->issuedBy($this->getDomainName())
            ->relatedTo($user->id)
            ->permittedFor($this->getDomainName())
            ->expiresAt($now + $ttl)
            ;
    }
}
