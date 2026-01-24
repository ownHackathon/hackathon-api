<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Domain;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Shared\Domain\Enum\TokenType;
use Shared\Trait\CloneReadonlyClassWith;
use Shared\Utils\Collectible;

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
