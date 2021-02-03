<?php

declare(strict_types=1);

/**
 * Contains the KnowsDomainName trait.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-09-20
 *
 */

namespace Konekt\BearerAuth\Auth;

use Illuminate\Support\Facades\URL;

trait KnowsDomainName
{
    private ?string $domain = null;

    private function getDomainName(): string
    {
        if (null === $this->domain) {
            $this->domain = explode('://', URL::to('/'))[1];

            if (str_contains($this->domain, ':')) {
                $this->domain = explode(':', $this->domain)[0];
            }
        }

        return $this->domain;
    }
}
