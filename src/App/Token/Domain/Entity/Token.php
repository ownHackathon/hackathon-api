<?php declare(strict_types=1);

namespace App\Token\Domain\Entity;

use App\Token\Api\Enum\TokenType;
use Core\SharedKernel\Trait\CloneReadonlyClassWith;
use Core\SharedKernel\Utils\Collectible;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

readonly final class Token implements TokenInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        public ?int $id,
        public int $accountId,
        public TokenType $tokenType,
        public UuidInterface $token,
        public DateTimeImmutable $createdAt,
    ) {
    }

    public function withId(int $id): TokenInterface
    {
        return self::with(id: $id);
    }

    public function withAccountId(int $accountId): TokenInterface
    {
        return self::with(accountId: $accountId);
    }

    public function withTokenType(TokenType $tokenType): TokenInterface
    {
        return self::with(tokenType: $tokenType);
    }

    public function withToken(UuidFactoryInterface $token): TokenInterface
    {
        return self::with(token: $token);
    }

    public function withCreatedAt(DateTimeImmutable $createdAt): TokenInterface
    {
        return self::with(createdAt: $createdAt);
    }
}
