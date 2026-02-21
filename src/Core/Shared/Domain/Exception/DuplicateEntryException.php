<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Domain\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface as HTTP;

use function print_r;
use function sprintf;

class DuplicateEntryException extends Exception
{
    public function __construct(string $entity, array $conflictIdentifier)
    {
        $message = sprintf('Entry for Entity %s already exists with: %s', $entity, print_r($conflictIdentifier, true));

        parent::__construct($message, HTTP::STATUS_BAD_REQUEST);
    }
}
