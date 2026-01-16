<?php declare(strict_types=1);

namespace App\Entity\Token;

use DateTimeImmutable;
use Core\Entity\Token\TokenInterface;
use Core\Enum\TokenType;
use Core\Trait\CloneReadonlyClassWith;
use Core\Utils\Collectible;
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
