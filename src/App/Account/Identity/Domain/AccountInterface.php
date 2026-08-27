<?php declare(strict_types=1);

namespace ownHackathon\App\Account\Identity\Domain;

use DateTimeImmutable;
use ownHackathon\App\Mailing\Domain\EmailType;
use Ramsey\Uuid\UuidInterface;

interface AccountInterface
{
    public const string AUTHENTICATED = 'account.authenticated.class';

    public ?int $id { get; }

    public UuidInterface $uuid { get; }

    public string $name { get; }

    public string $password { get; }

    public EmailType $email { get; }

    public DateTimeImmutable $registeredAt { get; }

    public ?DateTimeImmutable $lastActionAt { get; }

    public function with(mixed ...$properties): self;
}
