<?php declare(strict_types=1);

namespace ownHackathon\App\Entity\Token;

use DateTimeImmutable;
use ownHackathon\Core\Entity\Token\TokenInterface;
use ownHackathon\Core\Enum\TokenType;
use ownHackathon\Core\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\Utils\Collectible;
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
