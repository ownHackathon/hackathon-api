<?php declare(strict_types=1);

namespace App\Account\Identity\Domain;

use App\Mailing\Api\EmailType;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

interface AccountActivationInterface
{
    public ?int $id { get; }

    public EmailType $email { get; }

    public UuidInterface $token { get; }

    public DateTimeImmutable $createdAt { get; }

    public function withId(int $id): AccountActivationInterface;

    public function withEmail(EmailType $email): AccountActivationInterface;

    public function withToken(UuidInterface $token): AccountActivationInterface;

    public function withCreatedAt(DateTimeImmutable $createdAt): AccountActivationInterface;
}
