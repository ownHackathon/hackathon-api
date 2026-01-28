<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain\Exception;

use Exdrals\Shared\Domain\Exception\DuplicateEntryException;

class DuplicateAuthException extends DuplicateEntryException
{
    public function __construct(
        public string $account,
        public int $accountId,
        public string $clientId,
        public string $errorMessage,
    ) {
        parent::__construct($this->account, $this->accountId);
    }
}
