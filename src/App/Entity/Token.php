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
        private ?int $id,
        private int $accountId,
        private TokenType $tokenType,
        private UuidInterface $token,
        private DateTimeImmutable $createdAt,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAccountId(): int
    {
        return $this->accountId;
    }

    public function getTokenType(): TokenType
    {
        return $this->tokenType;
    }

    public function getToken(): UuidInterface
    {
        return $this->token;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
