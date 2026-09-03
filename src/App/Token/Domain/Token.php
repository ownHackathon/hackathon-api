<?php declare(strict_types=1);

namespace App\Token\Domain;

use App\Token\Domain\Enum\TokenType;
use Core\SharedKernel\Trait\CloneReadonlyClassWith;
use Core\SharedKernel\Utils\Collectible;
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
}
