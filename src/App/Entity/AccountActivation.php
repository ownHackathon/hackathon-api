<?php declare(strict_types=1);

namespace ownHackathon\App\Entity;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use ownHackathon\Core\Entity\Account\AccountActivationInterface;
use ownHackathon\Core\Trait\CloneReadonlyClassWith;
use ownHackathon\Core\Type\Email;
use ownHackathon\Core\Utils\Collectible;

readonly class AccountActivation implements AccountActivationInterface, Collectible
{
    use CloneReadonlyClassWith;

    public function __construct(
        private ?int $id,
        private Email $email,
        private UuidInterface $token,
        private DateTimeImmutable $createdAt,
    ) {
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): Email
    {
        return $this->email;
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
