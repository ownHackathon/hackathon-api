<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain\Exception;

use Exception;

final class SecurityBreachException extends Exception
{
    public function __construct(
        public string $expectedClientHash,
        public string $actualClientHash,
        public string $expectedUserAgent,
        public string $actualUserAgent,
    ) {
        parent::__construct();
    }
}
