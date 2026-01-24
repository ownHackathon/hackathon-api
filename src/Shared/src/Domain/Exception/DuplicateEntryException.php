<?php declare(strict_types=1);

namespace Exdrals\Shared\Domain\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface as HTTP;

use function sprintf;

class DuplicateEntryException extends Exception
{
    public function __construct(string $entity, ?int $id)
    {
        $message = sprintf('Entry for Entity %s with id %d already exists', $entity, $id);

        parent::__construct($message, HTTP::STATUS_BAD_REQUEST);
    }
}
