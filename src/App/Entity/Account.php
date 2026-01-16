<?php declare(strict_types=1);

namespace ownHackathon\App\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use ownHackathon\Core\Entity\Account\AccountInterface;
use ownHackathon\Core\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\Type\Email;
use ownHackathon\Core\Utils\Collectible;

readonly class Account implements AccountInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        private ?int $id,
        private UuidInterface $uuid,
        private string $name,
        private string $password,
        private Email $email,
        private DateTimeImmutable $registeredAt,
        private DateTimeImmutable $lastActionAt,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->password;
    }

    public function getRegisteredAt(): DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function getLastActionAt(): DateTimeImmutable
    {
        return $this->lastActionAt;
    }
}
