<?php

namespace App\Modules\Api\Http\Controllers;

use App\Http\Controllers\Controller;
use Konekt\BearerAuth\Auth\AuthorizesApiUser;
use Konekt\BearerAuth\Auth\TokenVerifier;
use Konekt\BearerAuth\Auth\VerifiesToken;
use Konekt\BearerAuth\Exceptions\ApiAuthorizationException;
use Konekt\BearerAuth\Http\Requests\AuthLoginRequest;
use Konekt\BearerAuth\Auth\TokenGenerator;
use Konekt\BearerAuth\Http\Requests\AuthTokenRequest;
use Exception;
use Illuminate\Support\Facades\Auth;
use Lcobucci\JWT\Parser;

class AuthController extends Controller
{
    use VerifiesToken, AuthorizesApiUser;

    /** @var TokenGenerator */
    private $tokenGenerator;

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

        if (!Auth::user()->type->isApi()) {
            return response(['message' => 'You are not allowed to access the API'], 403);
        }

        return response()->json([
            'access_token' => (string) $this->tokenGenerator->generateAccessToken(Auth::user()),
            'token_type' => 'Bearer',
            'expires_in' => $this->tokenGenerator->getAccessTokenTtl(),
            'refresh_token' => (string) $this->tokenGenerator->generateRefreshToken(Auth::user()),
        ]);
    }

    public function token(AuthTokenRequest $request)
    {
        try {
            $token = (new Parser())->parse($request->getRefreshToken());
        } catch (Exception $e) {
            throw new ApiAuthorizationException(400, 'Invalid token format');
        }

        $this->checkTokenSignature($token);
        $this->allowRefreshTokenOnly($token);

        if ($token->isExpired()) {
            throw new ApiAuthorizationException(401, 'Refresh token has expired. Use the login endpoint to get a new one');
        }

        $this->checkIssuer($token);
        $this->authenticateUser($this->tokenVerifier->getUserId($token));
        $this->checkIfUserIsActive();
        $this->checkIfUserApiAccessIsAllowed();

        return response()->json([
            'access_token' => (string) $this->tokenGenerator->generateAccessToken(Auth::user()),
            'token_type' => 'Bearer',
            'expires_in' => $this->tokenGenerator->getAccessTokenTtl(),
            /* @todo regenerate the refresh token if it expires sooner than the new access token */
            'refresh_token' => $request->getRefreshToken(),
        ]);
    }
}
