<?php declare(strict_types=1);

namespace App\Account\Identity\Domain;

use Core\SharedKernel\Trait\CloneReadonlyClassWith;
use Core\SharedKernel\Utils\Collectible;
use DateTimeImmutable;

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
