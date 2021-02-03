<?php

declare(strict_types=1);

/**
 * Contains the HasTokenConfig trait.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-02-03
 *
 */

namespace Konekt\BearerAuth\Auth;

use Lcobucci\JWT\Configuration;

trait HasTokenConfig
{
    use HasTokenSigner;

    private ?Configuration $configuration = null;

    private function getTokenConfig(): Configuration
    {
        if (!$this->configuration) {
            $this->configuration = Configuration::forSymmetricSigner($this->getSigner(), $this->getKey());
        }

        return $this->configuration;
    }
}
