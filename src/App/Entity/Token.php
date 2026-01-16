<?php declare(strict_types=1);

namespace ownHackathon\App\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use ownHackathon\App\Enum\TokenType;
use ownHackathon\Core\Entity\TokenInterface;
use ownHackathon\Core\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\Utils\Collectible;

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
