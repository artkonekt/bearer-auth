<?php

declare(strict_types=1);

/**
 * Contains the HasTokenSigner trait.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-09-20
 *
 */

namespace Konekt\BearerAuth\Auth;

use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Key\InMemory;

trait HasTokenSigner
{
    private ?Sha256 $signer = null;

    private ?Key $key = null;

    private function getSigner(): Sha256
    {
        if (null === $this->signer) {
            $this->signer = new Sha256();
        }

        return $this->signer;
    }

    private function getKey(): Key
    {
        if (null === $this->key) {
            $this->key = InMemory::plainText(config('konekt.bearer_auth.token_signature'));
        }

        return $this->key;
    }
}
