<?php declare(strict_types=1);

namespace App\Account\Identity\Domain\Exception;

use Exception;

final class AccountNotFoundException extends Exception
{
    public function __construct(
        public int $accountId = 0,
        public int $accessAuthId = 0,
        public string $email = '',
    ) {
        parent::__construct();
    }
}
