<?php
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

trait HasTokenSigner
{
    /** @var Sha256 */
    private $signer;

    /** @var Key|null */
    private $key;

    private function getKey(): Key
    {
        if (!$this->key) {
            $this->key = new Key(config('api.auth.token_signature'));
        }

        return $this->key;
    }
}
