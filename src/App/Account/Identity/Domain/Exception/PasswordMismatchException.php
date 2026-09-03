<?php declare(strict_types=1);

namespace App\Account\Identity\Domain\Exception;

use Exception;

final class PasswordMismatchException extends Exception
{
    public function __construct(
        public string $email = '',
    ) {
        parent::__construct();
    }
}
