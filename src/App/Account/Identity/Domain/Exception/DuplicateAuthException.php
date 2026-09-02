<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain\Exception;

use ownHackathon\Core\SharedKernel\Domain\Exception\DuplicateEntryException;

final class DuplicateAuthException extends DuplicateEntryException
{
    public function __construct(
        public string $account,
        public int $accountId,
        public string $clientId,
        public string $errorMessage,
    ) {
        parent::__construct($this->account, [
            'accountId' => $this->accountId,
            'clientId' => $this->clientId,
            'errorMessage' => $this->errorMessage,
        ]);
    }
}
