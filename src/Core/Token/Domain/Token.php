<?php declare(strict_types=1);

namespace Exdrals\Core\Token\Domain;

use DateTimeImmutable;
use Exdrals\Core\Shared\Domain\Enum\Token\TokenType;
use Exdrals\Core\Shared\Domain\Token\TokenInterface;
use Exdrals\Core\Shared\Trait\CloneReadonlyClassWith;
use Exdrals\Core\Shared\Utils\Collectible;
use Ramsey\Uuid\UuidInterface;

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
