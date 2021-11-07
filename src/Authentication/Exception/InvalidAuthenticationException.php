<?php declare(strict_types=1);

namespace Authentication\Exception;

use Exception;
use Throwable;

class InvalidAuthenticationException extends Exception
{
    public function __construct(string $message = "Invalid Authorization", int $code = 403, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
