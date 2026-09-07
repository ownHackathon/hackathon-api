<?php declare(strict_types=1);

namespace App\Token\Domain\Entity;

use App\Token\Api\Enum\TokenType;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

interface TokenInterface
{
    public ?int $id { get; }

    public int $accountId { get; }

    public TokenType $tokenType { get; }

    public UuidInterface $token { get; }

    public DateTimeImmutable $createdAt { get; }

    public function withId(int $id): self;

    public function withAccountId(int $accountId): self;

    public function withTokenType(TokenType $tokenType): self;

    public function withToken(UuidFactoryInterface $token): self;

    public function withCreatedAt(DateTimeImmutable $createdAt): self;
}
