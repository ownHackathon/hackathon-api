<?php declare(strict_types=1);

namespace Core\Entity\Account;

use DateTimeImmutable;
use Core\Type\Email;
use Ramsey\Uuid\UuidInterface;

interface AccountActivationInterface
{
    public ?int $id { get; }

    public Email $email { get; }

    public UuidInterface $token { get; }

    public DateTimeImmutable $createdAt { get; }

    public function with(mixed ...$properties): self;
}
