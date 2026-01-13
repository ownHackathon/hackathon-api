<?php declare(strict_types=1);

namespace ownHackathon\App\Entity;

use DateTimeImmutable;
use ownHackathon\Core\Entity\Account\AccountAccessAuthInterface;
use ownHackathon\Core\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\Utils\Collectible;

readonly class AccountAccessAuth implements AccountAccessAuthInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public int $id,
        public int $accountId,
        public string $label,
        public string $refreshToken,
        public string $userAgent,
        public string $clientIdentHash,
        public DateTimeImmutable $createdAt,
    ) {
    }
}
