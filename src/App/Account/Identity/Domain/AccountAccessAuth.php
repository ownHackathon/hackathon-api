<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain;

use DateTimeImmutable;
use ownHackathon\Core\Shared\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\Shared\Utils\Collectible;

readonly class AccountAccessAuth implements AccountAccessAuthInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public ?int $id,
        public int $accountId,
        public string $label,
        public string $refreshToken,
        public string $userAgent,
        public string $clientIdentHash,
        public DateTimeImmutable $createdAt,
    ) {
    }
}
