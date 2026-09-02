<?php declare(strict_types=1);

namespace ownHackathon\App\Token\Domain;

use DateTimeImmutable;
use ownHackathon\App\Token\Domain\Enum\TokenType;
use ownHackathon\Core\SharedKernel\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\SharedKernel\Utils\Collectible;
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
}
