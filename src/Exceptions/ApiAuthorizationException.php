<?php
/**
 * Contains the ApiAuthorizationException class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-09-20
 *
 */

namespace Konekt\BearerAuth\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiAuthorizationException extends HttpException
{
}
