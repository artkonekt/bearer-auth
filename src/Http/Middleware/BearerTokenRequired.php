<?php
/**
 * Contains the BearerTokenRequired class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 *
 * @since       2019-09-20
 */

namespace Konekt\BearerAuth\Http\Middleware;

use Konekt\BearerAuth\Auth\AuthorizesApiUser;
use Konekt\BearerAuth\Auth\TokenVerifier;
use Konekt\BearerAuth\Auth\VerifiesToken;
use Konekt\BearerAuth\Exceptions\ApiAuthorizationException;
use Konekt\BearerAuth\Auth\TokenGenerator;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lcobucci\JWT\Parser;

class BearerTokenRequired
{
    use VerifiesToken, AuthorizesApiUser;

    /** @var TokenGenerator */
    private $tokenGenerator;

    public function __construct(TokenGenerator $tokenGenerator, TokenVerifier $tokenVerifier)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->tokenVerifier = $tokenVerifier;
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
            $token = (new Parser())->parse(Str::after($request->header('Authorization'), 'Bearer '));
        } catch (Exception $e) {
            throw new ApiAuthorizationException(400, 'Invalid token format');
        }

        $this->checkTokenSignature($token);
        $this->allowAuthTokenOnly($token);

        if ($token->isExpired()) {
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
