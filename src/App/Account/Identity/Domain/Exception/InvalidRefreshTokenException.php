<?php declare(strict_types=1);

namespace App\Account\Identity\Domain\Exception;

use Exception;

final class InvalidRefreshTokenException extends Exception
{
    public function __construct(
        public string $refreshToken,
    ) {
        parent::__construct();
    }
}
