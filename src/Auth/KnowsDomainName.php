<?php
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
    /** @var string */
    private $domain;

    private function getDomainName(): string
    {
        if (!$this->domain) {
            $this->domain = explode('://', URL::to('/'))[1];

            if (false !== strpos($this->domain, ':')) {
                $this->domain = explode(':', $this->domain)[0];
            }
        }

        return $this->domain;
    }
}
