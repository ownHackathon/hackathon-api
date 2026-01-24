<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Domain;

use DateTimeImmutable;
use Shared\Trait\CloneReadonlyClassWith;
use Shared\Utils\Collectible;

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
