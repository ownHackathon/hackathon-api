<?php declare(strict_types=1);

namespace App\Account\Identity\Domain;

use App\Token\Api\DTO\RawTokenDto;
use DateTimeImmutable;

interface AccountAccessAuthInterface
{
    public ?int $id { get; }

    public int $accountId { get; }

    public string $label { get; }

    public RawTokenDto $refreshToken { get; }

    public string $userAgent { get; }

    public string $clientIdentHash { get; }

    public DateTimeImmutable $createdAt { get; }

    public function withId(int $id): self;

    public function withAccountId(int $accountId): self;

    public function withLabel(string $label): self;

    public function withRefreshToken(RawTokenDto $refreshToken): self;

    public function withUserAgent(string $userAgent): self;

    public function withClientIdentHash(string $clientIdentHash): self;
}
