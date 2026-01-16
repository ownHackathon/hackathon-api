<?php declare(strict_types=1);

namespace ownHackathon\Core\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use ownHackathon\App\Enum\TokenType;

interface TokenInterface
{
    public function getId(): ?int;

    public function getAccountId(): int;

    public function getTokenType(): TokenType;

    public function getToken(): UuidInterface;

    public function getCreatedAt(): DateTimeImmutable;
}
