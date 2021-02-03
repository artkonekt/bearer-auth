<?php

declare(strict_types=1);

/**
 * Contains the ApiAuthErrorResponseCreator class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-09-20
 *
 */

namespace Konekt\BearerAuth\Auth;

use Konekt\BearerAuth\Exceptions\ApiAuthorizationException;
use Illuminate\Http\JsonResponse;

/**
 * @see https://tools.ietf.org/html/rfc6750#page-7
 */
final class ApiAuthErrorResponseCreator
{
    use KnowsDomainName;

    public static function fromException(ApiAuthorizationException $exception): JsonResponse
    {
        return (new self())->getResponse($exception);
    }

    public function getResponse(ApiAuthorizationException $exception): JsonResponse
    {
        return new JsonResponse(
            ['message' => $exception->getMessage()],
            $exception->getStatusCode(),
            ['WWW-Authenticate' => 'Bearer ' . $this->getHeaderParameters($exception)]
        );
    }

    /**
     * @see https://tools.ietf.org/html/rfc6750#section-3.1
     */
    private function getHeaderParameters(ApiAuthorizationException $exception): string
    {
        $result = sprintf('realm="%s"', $this->getDomainName());

        switch ($exception->getStatusCode()) {
            case 400:
                $errorCode = 'invalid_request';

                break;
            case 401:
                $errorCode = 'invalid_token';

                break;
            case 403:
                $errorCode = 'insufficient_scope';

                break;
            default:
                $errorCode = null;

                break;
        }

        if (null !== $errorCode) {
            $result .= sprintf(
                ' error="%s", error_description="%s"',
                $errorCode,
                $exception->getMessage()
            );
        }

        return $result;
    }
}
