<?php declare(strict_types=1);

namespace Core\Exception;

use Fig\Http\Message\StatusCodeInterface as HTTP;
use Throwable;

class InvalidAuthenticationException extends ClientException
{
    public function __construct(string $message = 'Invalid Authorization', int $code = HTTP::STATUS_FORBIDDEN, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
