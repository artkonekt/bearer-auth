<?php

declare(strict_types=1);

/**
 * Contains the BearerAuthController class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-02-03
 *
 */

namespace Konekt\BearerAuth\Http\Controllers;

use Carbon\CarbonImmutable;
use Illuminate\Routing\Controller;
use Konekt\BearerAuth\Auth\AuthorizesApiUser;
use Konekt\BearerAuth\Auth\HasTokenConfig;
use Konekt\BearerAuth\Auth\TokenVerifier;
use Konekt\BearerAuth\Auth\VerifiesToken;
use Konekt\BearerAuth\Exceptions\ApiAuthorizationException;
use Konekt\BearerAuth\Http\Requests\AuthLoginRequest;
use Konekt\BearerAuth\Auth\TokenGenerator;
use Konekt\BearerAuth\Http\Requests\AuthTokenRequest;
use Exception;
use Illuminate\Support\Facades\Auth;

class BearerAuthController extends Controller
{
    use HasTokenConfig, VerifiesToken, AuthorizesApiUser;

    private TokenGenerator $tokenGenerator;

    public function __construct(TokenGenerator $tokenGenerator, TokenVerifier $tokenVerifier)
    {
        $this->tokenGenerator = $tokenGenerator;
        $this->tokenVerifier = $tokenVerifier;
    }

    public function login(AuthLoginRequest $request)
    {
        $credentials = [
            'email' => $request->getClientId(),
            'password' => $request->getClientSecret(),
        ];

        if (!Auth::once($credentials)) {
            return response(['message' => 'Invalid credentials supplied'], 401);
        }

        /** @todo make this optional, not all users might have type enum */
        if (!Auth::user()->type->isApi()) {
            return response(['message' => 'You are not allowed to access the API'], 403);
        }

        return response()->json([
            'access_token' => $this->tokenGenerator->generateAccessToken(Auth::user())->toString(),
            'token_type' => 'Bearer',
            'expires_in' => $this->tokenGenerator->getAccessTokenTtl(),
            'refresh_token' => $this->tokenGenerator->generateRefreshToken(Auth::user())->toString(),
        ]);
    }

    public function token(AuthTokenRequest $request)
    {
        try {
            $token = $this->getTokenConfig()->parser()->parse($request->getRefreshToken());
        } catch (Exception $e) {
            throw new ApiAuthorizationException(400, 'Invalid token format');
        }

        $this->checkTokenSignature($token);
        $this->allowRefreshTokenOnly($token);

        if ($token->isExpired(CarbonImmutable::now())) {
            throw new ApiAuthorizationException(401, 'Refresh token has expired. Use the login endpoint to get a new one');
        }

        $this->checkIssuer($token);
        $this->authenticateUser($this->tokenVerifier->getUserId($token));
        $this->checkIfUserIsActive();
        $this->checkIfUserApiAccessIsAllowed();

        return response()->json([
            'access_token' => $this->tokenGenerator->generateAccessToken(Auth::user())->toString(),
            'token_type' => 'Bearer',
            'expires_in' => $this->tokenGenerator->getAccessTokenTtl(),
            /** @todo regenerate the refresh token if it expires sooner than the new access token */
            'refresh_token' => $request->getRefreshToken(),
        ]);
    }
}
