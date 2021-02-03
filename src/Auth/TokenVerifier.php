<?php

declare(strict_types=1);

/**
 * Contains the TokenVerifier class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-09-20
 */

namespace Konekt\BearerAuth\Auth;

use Lcobucci\JWT\Token;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Lcobucci\JWT\Validation\Constraint\PermittedFor;
use Lcobucci\JWT\Validation\Constraint\SignedWith;
use Lcobucci\JWT\Validator;

class TokenVerifier
{
    use HasTokenConfig;
    use KnowsDomainName;

    public function __construct()
    {
        $this->configuration = $this->getTokenConfig();
    }

    public function isSignatureValid(Token $token): bool
    {
        return $this
            ->validator()
            ->validate($token, new SignedWith($this->getSigner(), $this->getKey()));
    }

    public function wasIssuedForUs(Token $token): bool
    {
        return $this
            ->validator()
            ->validate(
                $token,
                new IssuedBy($this->getDomainName()),
                new PermittedFor($this->getDomainName())
            );
    }

    public function getUserId(Token $token): ?int
    {
        return (int) $token->claims()->get('sub');
    }

    public function isRefreshToken(Token $token): bool
    {
        return $token->claims()->has('is_refresh');
    }

    private function validator(): Validator
    {
        return $this->configuration->validator();
    }
}
