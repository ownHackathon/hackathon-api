<?php declare(strict_types=1);

namespace ownHackathon\Core\Entity\Account;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use ownHackathon\Core\Type\Email;

interface AccountActivationInterface
{
    public function getId(): ?int;

    public function getEmail(): Email;

    public function getToken(): UuidInterface;

    public function getCreatedAt(): DateTimeImmutable;
}
