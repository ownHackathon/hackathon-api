<?php declare(strict_types=1);

namespace ownHackathon\Core\Token\Domain;

use DateTimeImmutable;
use ownHackathon\Core\Shared\Domain\Enum\Token\TokenType;
use ownHackathon\Core\Shared\Domain\Token\TokenInterface;
use ownHackathon\Core\Shared\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\Shared\Utils\Collectible;
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
