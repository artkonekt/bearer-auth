<?php

declare(strict_types=1);

/**
 * Contains the BearerTokenRequired class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-09-20
 */

namespace Konekt\BearerAuth\Http\Middleware;

use Carbon\CarbonImmutable;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Konekt\BearerAuth\Auth\AuthorizesApiUser;
use Konekt\BearerAuth\Auth\HasTokenConfig;
use Konekt\BearerAuth\Auth\TokenGenerator;
use Konekt\BearerAuth\Auth\TokenVerifier;
use Konekt\BearerAuth\Auth\VerifiesToken;
use Konekt\BearerAuth\Exceptions\ApiAuthorizationException;

class BearerTokenRequired
{
    use HasTokenConfig;
    use VerifiesToken;
    use AuthorizesApiUser;

    /** @var TokenGenerator */
    private TokenGenerator $tokenGenerator;

    public function __construct(TokenGenerator $tokenGenerator, TokenVerifier $tokenVerifier)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->tokenVerifier  = $tokenVerifier;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->hasHeader('Authorization')) {
            throw new ApiAuthorizationException(400, 'Missing Authorization token');
        }

        try {
            $token = $this->getTokenConfig()->parser()->parse(Str::after($request->header('Authorization'), 'Bearer '));
        } catch (Exception $e) {
            throw new ApiAuthorizationException(400, 'Invalid token format');
        }

        $this->checkTokenSignature($token);
        $this->allowAuthTokenOnly($token);

        if ($token->isExpired(CarbonImmutable::now())) {
            throw new ApiAuthorizationException(401, 'Expired token. Use the refresh token to get a new one');
        }

        $this->checkIssuer($token);
        $this->authenticateUser($this->tokenVerifier->getUserId($token));
        $this->checkIfUserIsActive();
        $this->checkIfUserApiAccessIsAllowed();
        $this->checkIfUserApiAccessIsAllowed();

        return $next($request);
    }
}
