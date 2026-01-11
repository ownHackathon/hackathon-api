<?php declare(strict_types=1);

namespace ownHackathon\Core\Entity\Account;

use DateTimeImmutable;
use ownHackathon\Core\Type\Email;
use Ramsey\Uuid\UuidInterface;

interface AccountInterface
{
    public const string AUTHENTICATED = 'account.authenticated.class';

    public ?int $id { get; }

    public UuidInterface $uuid { get; }

    public string $name { get; }

    public string $password { get; }

    public Email $email { get; }

    public DateTimeImmutable $registeredAt { get; }

    public DateTimeImmutable $lastActionAt { get; }

    public function with(mixed ...$properties): self;
}
