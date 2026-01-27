<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain\Exception;

use Exception;

class AccountNotFoundException extends Exception
{
    public function __construct(
        public int $accountId,
        public int $accessAuthId,
    ) {
        parent::__construct();
    }
}
