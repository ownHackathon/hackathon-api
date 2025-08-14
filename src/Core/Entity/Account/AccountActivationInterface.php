<?php declare(strict_types=1);

namespace ownHackathon\Core\Entity\Account;

use DateTimeImmutable;
use ownHackathon\Core\Type\Email;
use Ramsey\Uuid\UuidInterface;

interface AccountActivationInterface
{
    public function getId(): ?int;

    public function getEmail(): Email;

    public function getToken(): UuidInterface;

    public function getCreatedAt(): DateTimeImmutable;

    public function with(mixed ...$values): static;
}
