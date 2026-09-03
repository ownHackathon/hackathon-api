<?php declare(strict_types=1);

namespace App\Token\Domain;

use App\Token\Domain\Enum\TokenType;
use DateTimeImmutable;
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
