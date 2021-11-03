<?php declare(strict_types=1);

namespace Authentication\Exception;

use Exception;
use Throwable;

class InvalidAuthenticationException extends Exception
{
    public function __construct($message = "", $code = 403, Throwable $previous = null)
    {
        parent::__construct("Invalid Authorization", $code, $previous);
    }
}
