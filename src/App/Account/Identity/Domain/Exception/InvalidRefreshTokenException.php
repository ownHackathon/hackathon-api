<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain\Exception;

use Exception;

class InvalidRefreshTokenException extends Exception
{
    public function __construct(
        public string $refreshToken,
    ) {
        parent::__construct();
    }
}
