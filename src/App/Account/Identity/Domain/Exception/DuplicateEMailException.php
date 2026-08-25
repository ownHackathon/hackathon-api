<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain\Exception;

use Exception;

class DuplicateEMailException extends Exception
{
    public function __construct(
        public string $email,
    ) {
        parent::__construct();
    }
}
