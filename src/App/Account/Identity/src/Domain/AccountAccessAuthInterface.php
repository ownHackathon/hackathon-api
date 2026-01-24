<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Domain;

use DateTimeImmutable;

interface AccountAccessAuthInterface
{
    public ?int $id { get; }

    public int $accountId { get; }

    public string $label { get; }

    public string $refreshToken { get; }

    public string $userAgent { get; }

    public string $clientIdentHash { get; }

    public DateTimeImmutable $createdAt { get; }

    public function with(mixed ...$properties): self;
}
