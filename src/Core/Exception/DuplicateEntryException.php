<?php declare(strict_types=1);

namespace Core\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use JetBrains\PhpStorm\Pure;

use function sprintf;

class DuplicateEntryException extends Exception
{
    #[Pure] public function __construct(string $entity, string $uuid)
    {
        $message = sprintf('Entry for Enity %s with Uuid %s already exists', $entity, $uuid);

        parent::__construct($message, HTTP::STATUS_BAD_REQUEST);
    }
}
