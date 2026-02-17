<?php declare(strict_types=1);

namespace Exdrals\Core\Shared\Domain\Token;

use DateTimeImmutable;
use Exdrals\Core\Shared\Domain\Enum\Token\TokenType;
use Ramsey\Uuid\UuidInterface;

interface TokenInterface
{
    public ?int $id { get; }

    public int $accountId { get; }

    public TokenType $tokenType { get; }

    public UuidInterface $token { get; }

    public DateTimeImmutable $createdAt { get; }

    public function with(mixed ...$properties): self;
}
