<?php declare(strict_types=1);

namespace ownHackathon\Core\Entity\Account;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use ownHackathon\Core\Type\Email;

interface AccountInterface
{
    public const string AUTHENTICATED = 'account.authenticated.class';

    public function getId(): ?int;

    public function getUuid(): UuidInterface;

    public function getName(): string;

    public function getPasswordHash(): string;

    public function getEMail(): Email;

    public function getRegisteredAt(): DateTimeImmutable;

    public function getLastActionAt(): DateTimeImmutable;
}
