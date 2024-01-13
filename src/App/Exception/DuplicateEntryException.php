<?php declare(strict_types=1);

namespace App\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface as HTTP;
use JetBrains\PhpStorm\Pure;

use function sprintf;

class DuplicateEntryException extends Exception
{
    #[Pure] public function __construct(string $entity, int $id)
    {
        $message = sprintf('Entry for Enity %s with Id %d already exists', $entity, $id);

        parent::__construct($message, HTTP::STATUS_BAD_REQUEST);
    }
}
