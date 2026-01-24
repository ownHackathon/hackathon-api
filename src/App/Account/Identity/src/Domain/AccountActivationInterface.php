<?php declare(strict_types=1);

namespace Exdrals\Account\Identity\Domain;

use Exdrals\Mailing\Domain\EmailType;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

interface AccountActivationInterface
{
    public ?int $id { get; }

    public EmailType $email { get; }

    public UuidInterface $token { get; }

    public DateTimeImmutable $createdAt { get; }

    public function with(mixed ...$properties): self;
}
