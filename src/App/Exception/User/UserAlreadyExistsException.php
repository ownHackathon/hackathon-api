<?php declare(strict_types=1);

namespace App\Exception\User;

use Exception;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use JetBrains\PhpStorm\Pure;

class UserAlreadyExistsException extends Exception
{
    #[Pure] public function __construct(string $message = 'User already exists in the database')
    {
        parent::__construct($message, HTTP::STATUS_BAD_REQUEST);
    }
}
