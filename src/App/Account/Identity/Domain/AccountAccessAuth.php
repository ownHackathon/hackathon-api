<?php declare(strict_types=1);

namespace App\Account\Identity\Domain;

use App\Token\Api\DTO\RawTokenDto;
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
        public RawTokenDto $refreshToken,
        public string $userAgent,
        public string $clientIdentHash,
        public DateTimeImmutable $createdAt,
    ) {
    }

    public function withId(int $id): AccountAccessAuthInterface
    {
        return self::with(id: $id);
    }

    public function withAccountId(int $accountId): AccountAccessAuthInterface
    {
        return self::with(accountId: $accountId);
    }

    public function withLabel(string $label): AccountAccessAuthInterface
    {
        return self::with(label: $label);
    }

    public function withRefreshToken(RawTokenDto $refreshToken): AccountAccessAuthInterface
    {
        return self::with(refreshToken: $refreshToken);
    }

    public function withUserAgent(string $userAgent): AccountAccessAuthInterface
    {
        return self::with(userAgent: $userAgent);
    }

    public function withClientIdentHash(string $clientIdentHash): AccountAccessAuthInterface
    {
        return self::with(clientIdentHash: $clientIdentHash);
    }
}
