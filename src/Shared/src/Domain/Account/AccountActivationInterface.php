<?php declare(strict_types=1);

namespace Exdrals\Shared\Domain\Account;

use DateTimeImmutable;
use Exdrals\Mailing\Domain\EmailType;
use Ramsey\Uuid\UuidInterface;

interface AccountActivationInterface
{
    public ?int $id { get; }

    public EmailType $email { get; }

    public UuidInterface $token { get; }

    public DateTimeImmutable $createdAt { get; }

    public function with(mixed ...$properties): self;
}
