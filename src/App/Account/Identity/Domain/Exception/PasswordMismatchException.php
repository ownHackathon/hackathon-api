<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain\Exception;

use Exception;

class PasswordMismatchException extends Exception
{
    public function __construct(
        public string $email = '',
    ) {
        parent::__construct();
    }
}
