<?php declare(strict_types=1);

namespace ownHackathon\Core\SharedKernel\Domain\Exception;

use Exception;
use Fig\Http\Message\StatusCodeInterface as Http;

use function print_r;
use function sprintf;

class DuplicateEntryException extends Exception
{
    public function __construct(string $entity, array $conflictIdentifier)
    {
        $message = sprintf('Entry for Entity %s already exists with: %s', $entity, print_r($conflictIdentifier, true));

        parent::__construct($message, Http::STATUS_BAD_REQUEST);
    }
}
