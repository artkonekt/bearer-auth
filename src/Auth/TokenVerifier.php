<?php
/**
 * Contains the TokenParser class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 *
 * @since       2019-09-20
 */

namespace Konekt\BearerAuth\Auth;

use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;

class TokenVerifier
{
    use HasTokenSigner, KnowsDomainName;

    public function __construct(Sha256 $signer)
    {
        $this->signer = $signer;
    }

    public function isSignatureValid(Token $token): bool
    {
        return $token->verify($this->signer, $this->getKey());
    }

    public function wasIssuedForUs(Token $token): bool
    {
        $data = new ValidationData();
        $data->setIssuer($this->getDomainName());
        $data->setAudience($this->getDomainName());

        return $token->validate($data);
    }

    public function getUserId(Token $token): ?int
    {
        return $token->hasClaim('sub') ? (int) $token->getClaim('sub') : null;
    }

    public function isRefreshToken(Token $token): bool
    {
        return $token->hasClaim('is_refresh');
    }
}
