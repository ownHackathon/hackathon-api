<?php declare(strict_types=1);

namespace App\Account\Identity\Domain;

use App\Mailing\Api\EmailType;
use Core\SharedKernel\Utils\UuidFactoryInterface;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

interface AccountInterface
{
    public const string AUTHENTICATED = 'account.authenticated.class';

    public ?int $id { get; }

    public UuidInterface $uuid { get; }

    public string $name { get; }

    public string $hashedPassword { get; }

    public EmailType $email { get; }

    public DateTimeImmutable $registeredAt { get; }

    public ?DateTimeImmutable $lastActionAt { get; }

    public function withId(int $id): self;

    public function withUuid(UuidFactoryInterface $uuid): self;

    public function withName(string $name): self;

    public function withPasswordHash(string $hashedPassword): self;

    public function withEmail(EmailType $email): self;

    public function withRegisteredAt(DateTimeImmutable $registeredAt): self;

    public function refreshLastActionAt(): self;
}
