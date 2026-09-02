<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain;

use DateTimeImmutable;
use ownHackathon\Core\SharedKernel\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\SharedKernel\Utils\Collectible;

readonly final class AccountAccessAuth implements AccountAccessAuthInterface, Collectible
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
