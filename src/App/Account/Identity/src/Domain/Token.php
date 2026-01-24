<?php declare(strict_types=1);

namespace Exdrals\Identity\Domain;

use DateTimeImmutable;
use Exdrals\Identity\Domain\Enum\TokenType;
use Ramsey\Uuid\UuidInterface;
use Exdrals\Shared\Trait\CloneReadonlyClassWith;
use Exdrals\Shared\Utils\Collectible;

readonly class Token implements TokenInterface, Collectible
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
}
