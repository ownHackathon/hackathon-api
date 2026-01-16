<?php declare(strict_types=1);

namespace ownHackathon\Core\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use ownHackathon\App\Enum\TokenType;

interface TokenInterface
{
    public ?int $id { get; }

    public int $accountId { get; }

    public TokenType $tokenType { get; }

    public UuidInterface $token { get; }

    public DateTimeImmutable $createdAt { get; }

    public function with(mixed ...$properties): self;
}
